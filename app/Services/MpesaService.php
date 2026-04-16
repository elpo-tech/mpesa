<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MpesaService
{
    private string $consumerKey;
    private string $consumerSecret;
    private string $shortCode;
    private string $passKey;
    private string $callbackUrl;
    private string $baseUrl;

    public function __construct()
    {
        $this->consumerKey    = config('mpesa.consumer_key');
        $this->consumerSecret = config('mpesa.consumer_secret');
        $this->shortCode      = config('mpesa.shortcode');
        $this->passKey        = config('mpesa.passkey');
        $this->callbackUrl    = config('mpesa.callback_url');
        $this->baseUrl        = config('mpesa.sandbox')
            ? 'https://sandbox.safaricom.co.ke'
            : 'https://api.safaricom.co.ke';
    }

    public function getAccessToken(): string
    {
        return Cache::remember('mpesa_token', 3500, function () {
            $response = Http::withoutVerifying()
                ->withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->get("{$this->baseUrl}/oauth/v1/generate?grant_type=client_credentials");

            if ($response->failed()) {
                throw new \Exception('Failed to get M-Pesa access token: ' . $response->body());
            }

            return $response->json('access_token');
        });
    }

    public function stkPush(string $phone, int $amount, string $orderId): array
    {
        $timestamp = now()->format('YmdHis');
        $password  = base64_encode($this->shortCode . $this->passKey . $timestamp);

        $response = Http::withoutVerifying()
            ->withToken($this->getAccessToken())
            ->post("{$this->baseUrl}/mpesa/stkpush/v1/processrequest", [
                'BusinessShortCode' => $this->shortCode,
                'Password'          => $password,
                'Timestamp'         => $timestamp,
                'TransactionType'   => 'CustomerPayBillOnline',
                'Amount'            => $amount,
                'PartyA'            => $phone,
                'PartyB'            => $this->shortCode,
                'PhoneNumber'       => $phone,
                'CallBackURL'       => $this->callbackUrl,
                'AccountReference'  => $orderId,
                'TransactionDesc'   => 'Payment for ' . $orderId,
            ]);

        if ($response->failed() || isset($response['errorCode'])) {
            throw new \Exception($response->json('errorMessage') ?? 'STK push failed.');
        }

        return $response->json();
    }
}
