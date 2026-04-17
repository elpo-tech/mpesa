<?php

namespace App\Http\Controllers;

use App\Models\MpesaTransaction;
use Illuminate\Http\Request;

class Mainc extends Controller
{


    public function payments()
    {
        $transactions = MpesaTransaction::where('status', 'completed')
            ->latest('updated_at')
            ->paginate(20);

        $totalAmount = MpesaTransaction::where('status', 'completed')->sum('amount');

        $todayCount = MpesaTransaction::where('status', 'completed')
            ->whereDate('updated_at', today())
            ->count();

        return view('payment.payments', compact('transactions', 'totalAmount', 'todayCount'));
    }
}
