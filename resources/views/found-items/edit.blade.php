<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Found Item</title>
    <style>{!! file_get_contents(resource_path('css/found-items.css')) !!}</style>
</head>
<body>
<div class="container">
    <h1>Edit Found Item</h1>

    @if ($errors->any())
        <div class="alert alert-error">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('found-items.update', $item) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label for="item_name">Item name</label>
        <input type="text" id="item_name" name="item_name" value="{{ old('item_name', $item->item_name) }}" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4">{{ old('description', $item->description) }}</textarea>

        <label for="category_id">Category</label>
        <select id="category_id" name="category_id" required>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $item->category_id) == $category->id)>
                    {{ $category->category_name }}
                </option>
            @endforeach
        </select>

        <label for="location_id">Location found</label>
        <select id="location_id" name="location_id" required>
            @foreach ($locations as $location)
                <option value="{{ $location->id }}" @selected(old('location_id', $item->location_id) == $location->id)>
                    {{ $location->location_name }}
                </option>
            @endforeach
        </select>

        <label for="date_lost_found">Date found</label>
        <input type="date" id="date_lost_found" name="date_lost_found"
               value="{{ old('date_lost_found', $item->date_lost_found->format('Y-m-d')) }}"
               max="{{ now()->toDateString() }}" required>

        @if ($item->images->isNotEmpty())
            <label>Current images (tick to remove)</label>
            <div class="gallery">
                @foreach ($item->images as $image)
                    <label class="remove-box">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="">
                        <input type="checkbox" name="remove_images[]" value="{{ $image->id }}"> Remove
                    </label>
                @endforeach
            </div>
        @endif

        <label for="images">Add more images (max 5 at a time)</label>
        <input type="file" id="images" name="images[]" accept="image/*" multiple>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Update report</button>
            <a href="{{ route('found-items.show', $item) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>