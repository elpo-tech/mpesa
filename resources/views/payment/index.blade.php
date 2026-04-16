<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>M-Pesa Payment</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green: #1D9E75;
            --green-dark: #0F6E56;
            --green-light: #E1F5EE;
            --green-mid: #9FE1CB;
            --text: #1a1a1a;
            --muted: #6b7280;
            --border: #e5e7eb;
            --bg: #f9fafb;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
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
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 420px;
        }

        .logo-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
        }

        .mpesa-badge {
            background: var(--green);
            color: #fff;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 13px;
            letter-spacing: 0.5px;
            padding: 5px 12px;
            border-radius: 6px;
        }

        .secure-tag {
            font-size: 12px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .secure-tag svg { width: 12px; height: 12px; }

        h1 {
            font-family: 'Syne', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 0.4rem;
            line-height: 1.2;
        }

        .subtitle {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 2rem;
        }

        .amount-display {
            background: var(--green-light);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.75rem;
        }

        .amount-label { font-size: 13px; color: var(--green-dark); }
        .amount-value {
            font-family: 'Syne', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--green-dark);
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 6px;
        }

        .input-wrap {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .prefix {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: var(--muted);
            font-weight: 500;
            pointer-events: none;
        }

        input[type="tel"], input[type="number"] {
            width: 100%;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 12px 14px 12px 50px;
            font-size: 15px;
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            outline: none;
            transition: border-color 0.2s;
        }

        input[type="number"] { padding-left: 14px; }

        input:focus { border-color: var(--green); }

        .btn {
            width: 100%;
            background: var(--green);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn:hover { background: var(--green-dark); }
        .btn:active { transform: scale(0.98); }
        .btn:disabled { background: var(--muted); cursor: not-allowed; transform: none; }

        .spinner {
            width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            display: none;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .status-box {
            margin-top: 1.5rem;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            font-size: 14px;
            display: none;
        }

        .status-box.checking {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            color: #92400e;
        }

        .status-box.success {
            background: var(--green-light);
            border: 1px solid var(--green-mid);
            color: var(--green-dark);
        }

        .status-box.error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .status-title {
            font-weight: 600;
            margin-bottom: 4px;
            font-size: 14px;
        }

        .divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 1.75rem 0;
        }

        .footer-note {
            text-align: center;
            font-size: 12px;
            color: var(--muted);
        }

        .footer-note span { color: var(--green); font-weight: 500; }
    </style>
</head>
<body>

<div class="card">
    <div class="logo-row">
        <span class="mpesa-badge">M-PESA</span>
        <span class="secure-tag">
            <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 1L2 3.5V8C2 11.5 5 14.5 8 15C11 14.5 14 11.5 14 8V3.5L8 1Z" stroke="currentColor" stroke-width="1.5"/>
            </svg>
            Secure payment
        </span>
    </div>

    <h1>Complete your<br>payment</h1>
    <p class="subtitle">You'll receive a prompt on your phone to confirm.</p>

    <div class="amount-display">
        <span class="amount-label">Amount due</span>
        <span class="amount-value">KES {{ number_format($amount, 2) }}</span>
    </div>

    <form id="paymentForm">
        @csrf
        <input type="hidden" name="amount" value="{{ $amount }}">
        <input type="hidden" name="order_id" value="{{ $orderId ?? uniqid() }}">

        <div>
            <label for="phone">M-Pesa phone number</label>
            <div class="input-wrap">
                <span class="prefix">+254</span>
                <input type="tel" id="phone" name="phone" placeholder="7XX XXX XXX"
                       maxlength="9" pattern="[0-9]{9}" required autocomplete="tel">
            </div>
        </div>

        <button type="submit" class="btn" id="payBtn">
            <span id="btnText">Pay KES {{ number_format($amount, 2) }}</span>
            <div class="spinner" id="spinner"></div>
        </button>
    </form>

    <div class="status-box" id="statusBox">
        <div class="status-title" id="statusTitle"></div>
        <div id="statusMsg"></div>
    </div>

    <hr class="divider">
    <p class="footer-note">Payments processed via <span>Safaricom Daraja</span> API</p>
</div>

<script>
    const form = document.getElementById('paymentForm');
    const payBtn = document.getElementById('payBtn');
    const btnText = document.getElementById('btnText');
    const spinner = document.getElementById('spinner');
    const statusBox = document.getElementById('statusBox');
    const statusTitle = document.getElementById('statusTitle');
    const statusMsg = document.getElementById('statusMsg');

    let pollInterval = null;

    function showStatus(type, title, msg) {
        statusBox.className = 'status-box ' + type;
        statusBox.style.display = 'block';
        statusTitle.textContent = title;
        statusMsg.textContent = msg;
    }

    function setLoading(loading) {
        payBtn.disabled = loading;
        btnText.style.display = loading ? 'none' : 'block';
        spinner.style.display = loading ? 'block' : 'none';
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        const phone = document.getElementById('phone').value.trim();
        const amount = form.querySelector('[name=amount]').value;
        const orderId = form.querySelector('[name=order_id]').value;

        if (!/^[0-9]{9}$/.test(phone)) {
            showStatus('error', 'Invalid number', 'Enter a valid 9-digit Safaricom number (e.g. 712345678).');
            return;
        }

        setLoading(true);
        showStatus('checking', 'Sending request...', 'Initiating STK push to your phone.');

        try {
            const res = await fetch('/mpesa/stk-push', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({ phone: '254' + phone, amount, order_id: orderId })
            });

            const data = await res.json();

            if (data.success) {
                showStatus('checking', 'Check your phone', 'Enter your M-Pesa PIN to complete the payment. Waiting for confirmation...');
                pollPaymentStatus(data.checkout_request_id, orderId);
            } else {
                setLoading(false);
                showStatus('error', 'Request failed', data.message || 'Something went wrong. Please try again.');
            }
        } catch (err) {
            setLoading(false);
            showStatus('error', 'Network error', 'Could not reach the server. Check your connection and try again.');
        }
    });

    function pollPaymentStatus(checkoutRequestId, orderId) {
        let attempts = 0;
        const maxAttempts = 24; // ~2 minutes

        pollInterval = setInterval(async () => {
            attempts++;
            try {
                const res = await fetch(`/mpesa/status?checkout_request_id=${checkoutRequestId}&order_id=${orderId}`);
                const data = await res.json();

                if (data.status === 'completed') {
                    clearInterval(pollInterval);
                    showStatus('success', 'Payment confirmed!', 'Redirecting you now...');
                    setTimeout(() => { window.location.href = data.redirect_url || '/payment/success'; }, 1500);
                } else if (data.status === 'failed') {
                    clearInterval(pollInterval);
                    setLoading(false);
                    showStatus('error', 'Payment failed', data.message || 'The payment was not completed. Please try again.');
                } else if (attempts >= maxAttempts) {
                    clearInterval(pollInterval);
                    setLoading(false);
                    showStatus('error', 'Timed out', 'Payment confirmation took too long. If you paid, contact support.');
                }
            } catch (err) {
                // keep polling on network hiccups
            }
        }, 5000);
    }
</script>
</body>
</html>
