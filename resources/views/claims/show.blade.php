<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Details</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 750px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        h1 {
            color: #263b59;
        }

        .detail {
            margin: 20px 0;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .detail strong {
            display: block;
            color: #555;
            margin-bottom: 7px;
        }

        .status {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
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

        .back-btn {
            display: inline-block;
            background: #263b59;
            color: white;
            padding: 11px 18px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Claim Details</h1>

    <div class="detail">
        <strong>Claim Reference</strong>
        #{{ $claim->id }}
    </div>

    <div class="detail">
        <strong>Item Name</strong>
        {{ $claim->item->item_name ?? 'Item unavailable' }}
    </div>

    <div class="detail">
        <strong>Item Description</strong>
        {{ $claim->item->description ?? 'No description available.' }}
    </div>

    <div class="detail">
        <strong>Claim Explanation</strong>
        {{ $claim->claim_description }}
    </div>

    <div class="detail">
        <strong>Date Submitted</strong>
        {{ $claim->claimed_at?->format('d M Y, h:i A') ?? 'N/A' }}
    </div>

    <div class="detail">
        <strong>Claim Status</strong>

        <span class="status {{ strtolower($claim->claim_status) }}">
            {{ ucfirst($claim->claim_status) }}
        </span>
    </div>

    <div class="detail">
        <strong>Review Date</strong>

        {{ $claim->reviewed_at?->format('d M Y, h:i A') ?? 'Not reviewed yet' }}
    </div>

    <a href="{{ route('claims.index') }}" class="back-btn">
        Back to My Claims
    </a>

</div>

</body>
</html>
