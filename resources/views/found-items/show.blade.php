<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $item->item_name }}</title>
    <style>{!! file_get_contents(resource_path('css/found-items.css')) !!}</style>
</head>
<body>
<div class="container">
    <h1>{{ $item->item_name }}</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <span class="badge badge-{{ $item->status }}">{{ ucfirst($item->status) }}</span>

    <div class="details">
        <p><strong>Description:</strong><br>{{ $item->description ?: 'No description provided.' }}</p>
        <p><strong>Category:</strong> {{ $item->category->category_name }}</p>
        <p><strong>Location:</strong> {{ $item->location->location_name }}</p>
        <p><strong>Date found:</strong> {{ $item->date_lost_found->format('d M Y') }}</p>
        <p><strong>Date reported:</strong> {{ $item->created_at->format('d M Y, H:i') }}</p>
    </div>

    <h2>Images</h2>
    <div class="gallery">
        @forelse ($item->images as $image)
            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $item->item_name }}">
        @empty
            <p>No images uploaded.</p>
        @endforelse
    </div>

    <div class="actions">
        <a href="{{ route('found-items.index') }}" class="btn btn-secondary">Back to my items</a>
        @if ($item->status === 'open')
            <a href="{{ route('found-items.edit', $item) }}" class="btn btn-primary">Edit</a>
        @endif
    </div>
</div>
</body>
</html>