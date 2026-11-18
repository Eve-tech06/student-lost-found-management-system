<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Claims</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
        }

        h1 {
            color: #263b59;
        }

        .claim-card {
            background: white;
            padding: 22px;
            margin-bottom: 18px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }

        .status {
            display: inline-block;
            padding: 7px 13px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .pending {
            background: #fff1c2;
            color: #8a6500;
        }

        .approved {
            background: #d4edda;
            color: #155724;
        }

        .rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .view-btn {
            display: inline-block;
            background: #263b59;
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 12px;
        }

        .message {
            padding: 13px;
            margin-bottom: 15px;
            border-radius: 6px;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        .empty {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>My Claims</h1>

    <p>View and track the status of your submitted claims.</p>

    @if (session('success'))
        <div class="message success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="message error">
            {{ session('error') }}
        </div>
    @endif

    @forelse ($claims as $claim)

        <div class="claim-card">

            <h2>
                {{ $claim->item->item_name ?? 'Item unavailable' }}
            </h2>

            <p>
                <strong>Claim Reference:</strong>
                #{{ $claim->id }}
            </p>

            <p>
                <strong>Submitted On:</strong>
                {{ $claim->claimed_at?->format('d M Y, h:i A') ?? 'N/A' }}
            </p>

            <p>
                <strong>Claim Status:</strong>

                <span class="status {{ strtolower($claim->claim_status) }}">
                    {{ ucfirst($claim->claim_status) }}
                </span>
            </p>

            <a
                href="{{ route('claims.show', $claim->id) }}"
                class="view-btn"
            >
                View Claim Details
            </a>

        </div>

    @empty

        <div class="empty">
            <h2>No Claims Yet</h2>

            <p>You have not submitted any claims.</p>

        </div>

    @endforelse

</div>

</body>
</html>
