<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Lost and Found') · Student Lost and Found</title>
    <style>
        :root { color-scheme: light; font-family: system-ui, sans-serif; color: #172033; background: #f4f6fa; }
        * { box-sizing: border-box; }
        body { margin: 0; }
        header { background: #102a43; color: white; }
        nav, main { width: min(960px, calc(100% - 2rem)); margin: auto; }
        nav { display: flex; justify-content: space-between; align-items: center; min-height: 64px; }
        nav a { color: white; text-decoration: none; font-weight: 650; }
        main { padding: 2rem 0 4rem; }
        h1 { margin-top: 0; }
        .card { background: white; border: 1px solid #dbe2ea; border-radius: 12px; padding: 1.25rem; margin: 1rem 0; }
        label { display: block; font-weight: 650; margin: 1rem 0 .35rem; }
        input, textarea, select { width: 100%; padding: .7rem; border: 1px solid #aebdca; border-radius: 7px; font: inherit; }
        textarea { min-height: 120px; resize: vertical; }
        button, .button { display: inline-block; border: 0; border-radius: 7px; padding: .7rem 1rem; background: #087e8b; color: white; font: inherit; font-weight: 650; text-decoration: none; cursor: pointer; }
        .button.secondary { background: #526477; }
        .actions { display: flex; flex-wrap: wrap; gap: .6rem; margin-top: 1.25rem; }
        .notice { padding: .85rem 1rem; border-radius: 7px; background: #e4f6ec; color: #14532d; }
        .errors { background: #fff0f0; color: #8a1c1c; }
        .muted { color: #5c6b7a; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: .75rem; border-bottom: 1px solid #e2e8f0; }
        @media (max-width: 600px) { nav { align-items: flex-start; flex-direction: column; justify-content: center; gap: .25rem; padding: .75rem 0; } }
    </style>
</head>
<body>
<header>
    <nav>
        <a href="{{ route('lost-items.index') }}">Student Lost and Found</a>
        <a href="{{ route('lost-items.create') }}">Report a lost item</a>
    </nav>
</header>
<main>
    @if (session('success'))
        <p class="notice" role="status">{{ session('success') }}</p>
    @endif
    @if ($errors->any())
        <div class="notice errors" role="alert">
            <strong>Please correct the following:</strong>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
    @yield('content')
</main>
</body>
</html>
