<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Found Items</title>
    <style>{!! file_get_contents(resource_path('css/found-items.css')) !!}</style>
</head>
<body>
<div class="container">
    <div class="header-row">
        <h1>My Found Items</h1>
        <a href="{{ route('found-items.create') }}" class="btn btn-primary">+ Report found item</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse ($items as $item)
        <div class="card">
            @if ($item->images->isNotEmpty())
                <img class="thumb" src="{{ asset('storage/' . $item->images->first()->image_path) }}"
                     alt="{{ $item->item_name }}">
            @else
                <div class="thumb placeholder">No image</div>
            @endif

            <div class="card-body">
                <h2>{{ $item->item_name }}</h2>
                <p><strong>Category:</strong> {{ $item->category->category_name }}</p>
                <p><strong>Location:</strong> {{ $item->location->location_name }}</p>
                <p><strong>Date found:</strong> {{ $item->date_lost_found->format('d M Y') }}</p>
                <span class="badge badge-{{ $item->status }}">{{ ucfirst($item->status) }}</span>

                <div class="actions">
                    <a href="{{ route('found-items.show', $item) }}" class="btn btn-primary">View details</a>
                    @if ($item->status === 'open')
                        <a href="{{ route('found-items.edit', $item) }}" class="btn btn-secondary">Edit</a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="empty">You haven't reported any found items yet.</div>
    @endforelse
</div>
</body>
</html>