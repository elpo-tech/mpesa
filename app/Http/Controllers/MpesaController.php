<?php

namespace App\Http\Controllers;

use App\Models\MpesaTransaction;
use App\Services\MpesaService;
use Illuminate\Http\Request;

class MpesaController extends Controller
{
    public function __construct(protected MpesaService $mpesa) {}

    /**
     * Show the payment form.
     * Route: GET /payment
     */
    public function index(Request $request)
    {
        return view('payment.index', [
            'amount'  => $request->query('amount', 1),
            'orderId' => $request->query('order_id', uniqid('order_')),
        ]);
    }

    /**
     * Initiate STK Push.
     * Route: POST /mpesa/stk-push
     */
    public function stkPush(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string|regex:/^2547[0-9]{8}$/',
            'amount'   => 'required|numeric|min:1',
            'order_id' => 'required|string',
        ]);

        try {
            $response = $this->mpesa->stkPush(
                phone:   $request->phone,
                amount:  (int) $request->amount,
                orderId: $request->order_id
            );

            // Store transaction
            MpesaTransaction::create([
                'order_id'            => $request->order_id,
                'phone'               => $request->phone,
                'amount'              => $request->amount,
                'merchant_request_id' => $response['MerchantRequestID'],
                'checkout_request_id' => $response['CheckoutRequestID'],
                'status'              => 'pending',
            ]);

            return response()->json([
                'success'             => true,
                'checkout_request_id' => $response['CheckoutRequestID'],
                'message'             => 'STK push sent.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Poll payment status.
     * Route: GET /mpesa/status
     */
    public function status(Request $request)
    {
        $transaction = MpesaTransaction::where('checkout_request_id', $request->checkout_request_id)->first();

        if (! $transaction) {
            return response()->json(['status' => 'pending']);
        }

        if ($transaction->status === 'completed') {
            return response()->json([
                'status'       => 'completed',
                'redirect_url' => route('payment.success', ['order_id' => $transaction->order_id]),
            ]);
        }

        if ($transaction->status === 'failed') {
            return response()->json([
                'status'  => 'failed',
                'message' => $transaction->result_desc ?? 'Payment was not completed.',
            ]);
        }

        return response()->json(['status' => 'pending']);
    }

    /**
     * M-Pesa callback (called by Safaricom).
     * Route: POST /mpesa/callback
     */
    public function callback(Request $request)
    {
        $body = $request->input('Body.stkCallback');

        if (! $body) {
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        $checkoutRequestId = $body['CheckoutRequestID'];
        $resultCode        = $body['ResultCode'];

        $transaction = MpesaTransaction::where('checkout_request_id', $checkoutRequestId)->first();

        if (! $transaction) {
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        if ($resultCode === 0) {
            // Payment successful — extract metadata
            $items  = collect($body['CallbackMetadata']['Item']);
            $receipt = $items->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;
            $phone   = $items->firstWhere('Name', 'PhoneNumber')['Value'] ?? null;

            $transaction->update([
                'status'         => 'completed',
                'mpesa_receipt'  => $receipt,
                'phone'          => $phone ?? $transaction->phone,
                'result_code'    => $resultCode,
                'result_desc'    => $body['ResultDesc'],
            ]);
        } else {
            $transaction->update([
                'status'      => 'failed',
                'result_code' => $resultCode,
                'result_desc' => $body['ResultDesc'],
            ]);
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    /**
     * Payment success page.
     * Route: GET /payment/success
     */
    public function success(Request $request)
    {
        $transaction = MpesaTransaction::where('order_id', $request->order_id)->first();

        return view('payment.success', [
            'transaction' => $transaction,
            'redirectUrl' => '/',
        ]);
    }
}
