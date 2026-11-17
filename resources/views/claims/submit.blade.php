<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Claim</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 650px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        h1 {
            color: #263b59;
        }

        .item-details {
            background: #f1f5fa;
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        textarea {
            width: 100%;
            min-height: 130px;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            resize: vertical;
        }

        .buttons {
            margin-top: 20px;
            display: flex;
            gap: 12px;
        }

        button {
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        .submit-btn {
            background: #263b59;
            color: white;
        }

        .cancel-btn {
            background: #ddd;
            color: #333;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Submit a Claim</h1>

    <p>Please provide information to help verify that this item belongs to you.</p>

    <div class="item-details">
        <h3>Item Details</h3>

        <p>
            <strong>Item Name:</strong>
            {{ $item->item_name }}
        </p>

        <p>
            <strong>Description:</strong>
            {{ $item->description ?? 'No description provided.' }}
        </p>

        <p>
            <strong>Report Type:</strong>
            {{ ucfirst($item->report_type) }}
        </p>

        <p>
            <strong>Date Reported:</strong>
            {{ $item->date_lost_found }}
        </p>
    </div>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('claims.store', $item->id) }}" method="POST">

        @csrf

        <label for="claim_description">
            Explain why you believe this item belongs to you:
        </label>

        <textarea
            id="claim_description"
            name="claim_description"
            placeholder="Describe identifying features, contents, or other details that can prove ownership..."
            required
        >{{ old('claim_description') }}</textarea>

        <div class="buttons">

            <button type="submit" class="submit-btn">
                Submit Claim
            </button>

            <button
                type="button"
                class="cancel-btn"
                onclick="history.back()"
            >
                Cancel
            </button>

        </div>

    </form>

</div>

</body>
</html>
