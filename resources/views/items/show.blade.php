<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $item->item_name }} | Lost & Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .main-img { max-height: 380px; width: 100%; object-fit: cover; }
        .thumb { height: 80px; width: 100%; object-fit: cover; }
    </style>
</head>
<body class="bg-light">
<div class="container py-4">

    <a href="{{ route('items.index') }}" class="btn btn-outline-secondary btn-sm mb-3">&larr; Back to items</a>

    <div class="card shadow-sm">
        <div class="row g-0">

            {{-- Images --}}
            <div class="col-12 col-md-6 p-3">
                @if ($item->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $item->images->first()->image_path) }}"
                         class="main-img rounded mb-2" alt="{{ $item->item_name }}">

                    @if ($item->images->count() > 1)
                        <div class="row g-2">
                            @foreach ($item->images->skip(1) as $image)
                                <div class="col-4">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                         class="thumb rounded" alt="{{ $item->item_name }}">
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded"
                         style="height: 300px;">No image available</div>
                @endif
            </div>

            {{-- Details --}}
            <div class="col-12 col-md-6 p-3">
                <span class="badge {{ $item->report_type === 'lost' ? 'bg-danger' : 'bg-success' }} mb-2">
                    {{ ucfirst($item->report_type) }}
                </span>
                <h1 class="h3">{{ $item->item_name }}</h1>
                <p class="mt-3">{{ $item->description }}</p>

                <table class="table table-sm">
                    <tbody>
                        <tr><th scope="row">Category</th><td>{{ $item->category->name ?? 'N/A' }}</td></tr>
                        <tr><th scope="row">Location</th><td>{{ $item->location->name ?? 'N/A' }}</td></tr>
                        <tr>
                            <th scope="row">Date {{ $item->report_type }}</th>
                            <td>{{ $item->date_lost_found ? \Carbon\Carbon::parse($item->date_lost_found)->format('d M Y') : 'N/A' }}</td>
                        </tr>
                        <tr><th scope="row">Date reported</th><td>{{ $item->created_at->format('d M Y') }}</td></tr>
                        <tr>
                            <th scope="row">Status</th>
                            <td><span class="badge bg-secondary">{{ ucfirst($item->status) }}</span></td>
                        </tr>
                        @if ($item->reporter)
                            <tr><th scope="row">Reported by</th><td>{{ $item->reporter->name }}</td></tr>
                        @endif
                    </tbody>
                </table>

                {{-- Claim button: eligible found items only --}}
                @if ($canClaim)
                    <a href="{{ Route::has('claims.create') ? route('claims.create', $item->id) : '#' }}"
                       class="btn btn-success w-100">Claim Item</a>
                @endif
            </div>

        </div>
    </div>
</div>
</body>
</html>