<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Browse Lost & Found Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .item-img { height: 180px; object-fit: cover; background: #f1f3f5; }
        .no-img { height: 180px; background: #f1f3f5; color: #868e96; }
    </style>
</head>
<body class="bg-light">
<div class="container py-4">

    <h1 class="h3 mb-4">Browse Lost &amp; Found Items</h1>

    {{-- Search + filters --}}
    <form method="GET" action="{{ route('items.index') }}" class="card card-body mb-4">
        <div class="row g-2 mb-2">
            <div class="col-12 col-md-9">
                <input type="text" name="q" class="form-control"
                       placeholder="Search by item name or description..."
                       value="{{ $filters['q'] ?? '' }}">
            </div>
            <div class="col-12 col-md-3 d-grid">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>

        <div class="row g-2">
            <div class="col-6 col-md-3">
                <select name="report_type" class="form-select">
                    <option value="">Lost &amp; Found</option>
                    @foreach ($reportTypes as $type)
                        <option value="{{ $type }}" @selected(($filters['report_type'] ?? '') === $type)>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="location_id" class="form-select">
                    <option value="">All locations</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}" @selected(($filters['location_id'] ?? '') == $location->id)>
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-3 d-flex gap-2">
            <button type="submit" class="btn btn-outline-primary btn-sm">Apply filters</button>
            <a href="{{ route('items.index') }}" class="btn btn-outline-secondary btn-sm">Clear filters</a>
        </div>
    </form>

    {{-- Results --}}
    @if ($items->isEmpty())
        <div class="alert alert-info text-center">
            No matching items found. Try a different keyword or clear your filters.
        </div>
    @else
        <p class="text-muted">{{ $items->total() }} item(s) found</p>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
            @foreach ($items as $item)
                @php $image = $item->images->first(); @endphp
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        @if ($image)
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                 class="card-img-top item-img" alt="{{ $item->item_name }}">
                        @else
                            <div class="no-img d-flex align-items-center justify-content-center">No image</div>
                        @endif

                        <div class="card-body">
                            <span class="badge {{ $item->report_type === 'lost' ? 'bg-danger' : 'bg-success' }} mb-2">
                                {{ ucfirst($item->report_type) }}
                            </span>
                            <h5 class="card-title">{{ $item->item_name }}</h5>
                            <p class="card-text text-muted">{{ \Illuminate\Support\Str::limit($item->description, 90) }}</p>
                            <ul class="list-unstyled small mb-0">
                                <li><strong>Category:</strong> {{ $item->category->name ?? 'N/A' }}</li>
                                <li><strong>Location:</strong> {{ $item->location->name ?? 'N/A' }}</li>
                                <li><strong>Reported:</strong> {{ $item->created_at->format('d M Y') }}</li>
                            </ul>
                        </div>

                        <div class="card-footer bg-white border-0 pb-3">
                            <a href="{{ route('items.show', $item->id) }}" class="btn btn-primary w-100">View details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $items->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>
</body>
</html>