<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&family=Instrument+Serif:ital@0;1&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --ink: #0f1117;
            --ink-2: #3d4148;
            --ink-3: #6b7280;
            --surface: #ffffff;
            --surface-2: #f5f5f3;
            --surface-3: #ebebea;
            --green: #1D9E75;
            --green-light: #E1F5EE;
            --green-dark: #0F6E56;
            --accent: #0f1117;
            --border: #e2e2e0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--surface-2);
            color: var(--ink);
            min-height: 100vh;
        }

        /* ── Top bar ── */
        .topbar {
            background: var(--ink);
            padding: 0 2rem;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-dot {
            width: 8px;
            height: 8px;
            background: var(--green);
            border-radius: 50%;
        }

        .brand-name {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: #fff;
            letter-spacing: 0.5px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-date {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.4);
            font-family: 'JetBrains Mono', monospace;
        }

        .logout-btn {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 5px 12px;
            border-radius: 6px;
            transition: all 0.15s;
        }

        .logout-btn:hover {
            color: #fff;
            border-color: rgba(255, 255, 255, 0.4);
        }

        /* ── Layout ── */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-family: 'Instrument Serif', serif;
            font-size: 2rem;
            color: var(--ink);
            line-height: 1.1;
            margin-bottom: 0.3rem;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--ink-3);
        }

        /* ── Stats ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
        }

        .stat-label {
            font-size: 11px;
            font-family: 'JetBrains Mono', monospace;
            color: var(--ink-3);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-family: 'Instrument Serif', serif;
            font-size: 1.9rem;
            color: var(--ink);
            line-height: 1;
        }

        .stat-value.green {
            color: var(--green-dark);
        }

        /* ── Table card ── */
        .table-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }

        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .table-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--ink);
        }

        .badge {
            background: var(--green-light);
            color: var(--green-dark);
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            font-size: 11px;
            font-family: 'JetBrains Mono', monospace;
            color: var(--ink-3);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            text-align: left;
            background: var(--surface-2);
            border-bottom: 1px solid var(--border);
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.1s;
            animation: fadeIn 0.3s ease both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background: var(--surface-2);
        }

        tbody td {
            padding: 1rem 1.5rem;
            font-size: 14px;
            color: var(--ink-2);
            vertical-align: middle;
        }

        .td-primary {
            font-weight: 500;
            color: var(--ink);
        }

        .td-mono {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--ink-2);
        }

        .td-amount {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 500;
            color: var(--green-dark);
            font-size: 13px;
        }

        .td-date {
            font-size: 12px;
            color: var(--ink-3);
        }

        .td-date .date {
            display: block;
            font-weight: 500;
            color: var(--ink-2);
            font-size: 13px;
        }

        .td-date .time {
            display: block;
            font-size: 11px;
            font-family: 'JetBrains Mono', monospace;
        }

        .avatar {
            width: 32px;
            height: 32px;
            background: var(--surface-3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            color: var(--ink-2);
            flex-shrink: 0;
        }

        .phone-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--ink-3);
        }

        .empty-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        .empty-state p {
            font-size: 14px;
        }

        /* ── Responsive ── */
        @media (max-width: 700px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .hide-mobile {
                display: none;
            }

            tbody td,
            thead th {
                padding: 0.75rem 1rem;
            }
        }
    </style>
</head>

<body>

    <nav class="topbar">
        <div class="topbar-brand">
            <div class="brand-dot"></div>
            <span class="brand-name">PAYMENTS / ADMIN</span>
        </div>
        <div class="topbar-right">
            <span class="topbar-date" id="liveClock"></span>
            <a href="#" class="logout-btn">Sign out</a>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Completed payments</h1>
            <p class="page-subtitle">All verified M-Pesa transactions</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total collected</div>
                <div class="stat-value green">KES {{ number_format($totalAmount, 2) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Transactions</div>
                <div class="stat-value">{{ $transactions->total() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Today</div>
                <div class="stat-value">{{ $todayCount }}</div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-header">
                <span class="table-title">Transaction log</span>
                <span class="badge">{{ $transactions->total() }} completed</span>
            </div>

            @if($transactions->count())
            <table>
                <thead>
                    <tr>
                        <th>Phone / Payer</th>
                        <th>Amount</th>
                        <th>M-Pesa code</th>
                        <th class="hide-mobile">Account ref</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $i => $tx)
                    <tr style="animation-delay: {{ $i * 0.04 }}s">
                        <td>
                            <div class="phone-cell">
                                <div class="avatar">
                                    {{ strtoupper(substr(preg_replace('/^254/', '', $tx->phone), 0, 2)) }}
                                </div>
                                <div>
                                    <div class="td-primary">{{ $tx->phone }}</div>
                                    @if($tx->payer_name)
                                    <div style="font-size:12px; color: var(--ink-3);">{{ $tx->payer_name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="td-amount">KES {{ number_format($tx->amount, 2) }}</td>
                        <td class="td-mono">{{ $tx->mpesa_receipt ?? '—' }}</td>
                        <td class="hide-mobile td-mono">{{ $tx->order_id }}</td>
                        <td class="td-date">
                            <span class="date">{{ $tx->updated_at->format('d M Y') }}</span>
                            <span class="time">{{ $tx->updated_at->format('H:i:s') }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($transactions->hasPages())
            <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border); font-size: 13px; color: var(--ink-3); display: flex; justify-content: space-between; align-items: center;">
                <span>Showing {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} of {{ $transactions->total() }}</span>
                <div style="display: flex; gap: 8px;">
                    @if($transactions->onFirstPage())
                    <span style="opacity:0.3; cursor:default;">← Prev</span>
                    @else
                    <a href="{{ $transactions->previousPageUrl() }}" style="color: var(--green); text-decoration: none; font-weight: 500;">← Prev</a>
                    @endif
                    @if($transactions->hasMorePages())
                    <a href="{{ $transactions->nextPageUrl() }}" style="color: var(--green); text-decoration: none; font-weight: 500;">Next →</a>
                    @else
                    <span style="opacity:0.3; cursor:default;">Next →</span>
                    @endif
                </div>
            </div>
            @endif

            @else
            <div class="empty-state">
                <div class="empty-icon">◎</div>
                <p>No completed transactions yet.</p>
            </div>
            @endif
        </div>
    </div>

    <script>
        function tick() {
            const now = new Date();
            document.getElementById('liveClock').textContent = now.toLocaleTimeString('en-KE', {
                hour12: false
            });
        }
        tick();
        setInterval(tick, 1000);
    </script>
</body>

</html>