<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Lost and Found</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
        }

        .navbar {
            background: #2563eb;
            color: white;
            padding: 20px 40px;
        }

        .container {
            padding: 40px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        button {
            background: #dc2626;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <h2>Lost and Found Management System</h2>
    </div>

    <div class="container">
        <div class="card">

            <h1>Welcome, {{ Auth::user()->first_name }}!</h1>

            <p>You are successfully logged in.</p>

            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>

            <p><strong>Role:</strong> {{ Auth::user()->role }}</p>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>

        </div>
    </div>

</body>
</html>