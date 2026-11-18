<label for="item_name">Item name</label>
<input id="item_name" name="item_name" value="{{ old('item_name', $item->item_name ?? '') }}" maxlength="255" required>

<label for="description">Description</label>
<textarea id="description" name="description" maxlength="5000">{{ old('description', $item->description ?? '') }}</textarea>

<label for="category_id">Category</label>
<select id="category_id" name="category_id" required>
    <option value="">Choose a category</option>
    @foreach ($categories as $category)
        <option value="{{ $category->id }}" @selected((string) old('category_id', $item->category_id ?? '') === (string) $category->id)>{{ $category->category_name }}</option>
    @endforeach
</select>

<label for="location_id">Where did you last see it?</label>
<select id="location_id" name="location_id" required>
    <option value="">Choose a location</option>
    @foreach ($locations as $location)
        <option value="{{ $location->id }}" @selected((string) old('location_id', $item->location_id ?? '') === (string) $location->id)>{{ $location->location_name }}</option>
    @endforeach
</select>

<label for="date_lost_found">Date lost</label>
<input id="date_lost_found" type="date" name="date_lost_found" value="{{ old('date_lost_found', isset($item) ? $item->date_lost_found->format('Y-m-d') : '') }}" max="{{ now()->toDateString() }}" required>

@if ($categories->isEmpty() || $locations->isEmpty())
    <p class="notice errors">Categories and locations must be added before you can submit a report.</p>
@endif
<div class="actions">
    <button type="submit" @disabled($categories->isEmpty() || $locations->isEmpty())>{{ $submitLabel }}</button>
    <a class="button secondary" href="{{ isset($item) ? route('lost-items.show', $item) : route('lost-items.index') }}">Cancel</a>
</div>
