<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --green: #1D9E75;
            --green-dark: #0F6E56;
            --green-light: #E1F5EE;
            --text: #1a1a1a;
            --muted: #6b7280;
            --border: #e5e7eb;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f9fafb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid var(--border);
            padding: 3rem 2rem;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .check-circle {
            width: 72px;
            height: 72px;
            background: var(--green-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: pop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes pop {
            from { transform: scale(0); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }

        .check-circle svg { width: 36px; height: 36px; }

        h1 {
            font-family: 'Syne', sans-serif;
            font-size: 1.7rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 0.5rem;
        }

        .subtitle { font-size: 14px; color: var(--muted); margin-bottom: 2rem; }

        .receipt {
            background: var(--green-light);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 2rem;
            text-align: left;
        }

        .receipt-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            padding: 5px 0;
        }

        .receipt-row .label { color: var(--green-dark); }
        .receipt-row .value { font-weight: 600; color: var(--green-dark); }

        .divider { border: none; border-top: 0.5px solid #9FE1CB; margin: 8px 0; }

        .btn {
            display: inline-block;
            background: var(--green);
            color: #fff;
            text-decoration: none;
            border-radius: 12px;
            padding: 13px 2rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            font-weight: 600;
            transition: background 0.2s;
        }

        .btn:hover { background: var(--green-dark); }
    </style>
</head>
<body>
<div class="card">
    <div class="check-circle">
        <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M8 18L15 25L28 11" stroke="#1D9E75" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>

    <h1>Payment received</h1>
    <p class="subtitle">Your M-Pesa transaction was successful.</p>

    <div class="receipt">
        @if(isset($transaction))
        <div class="receipt-row">
            <span class="label">Amount</span>
            <span class="value">KES {{ number_format($transaction->amount, 2) }}</span>
        </div>
        <div class="divider"></div>
        <div class="receipt-row">
            <span class="label">M-Pesa code</span>
            <span class="value">{{ $transaction->mpesa_receipt ?? 'N/A' }}</span>
        </div>
        <div class="receipt-row">
            <span class="label">Phone</span>
            <span class="value">{{ $transaction->phone }}</span>
        </div>
        <div class="receipt-row">
            <span class="label">Date</span>
            <span class="value">{{ $transaction->updated_at->format('d M Y, H:i') }}</span>
        </div>
        @endif
    </div>

    <a href="{{ $redirectUrl ?? '/' }}" class="btn">Continue</a>
</div>
</body>
</html>
