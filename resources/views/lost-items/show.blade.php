@extends('layouts.app')

@section('title', 'Lost Item Details')

@section('content')
    <p><a href="{{ route('lost-items.index') }}">← My lost items</a></p>
    <article class="card">
        <h1>{{ $item->item_name }}</h1>
        <p><strong>Status:</strong> {{ ucfirst($item->status) }}</p>
        <p><strong>Category:</strong> {{ $item->category->category_name }}</p>
        <p><strong>Last seen at:</strong> {{ $item->location->location_name }}</p>
        <p><strong>Date lost:</strong> {{ $item->date_lost_found->format('F j, Y') }}</p>
        <p><strong>Description:</strong><br>{{ $item->description ?: 'No description provided.' }}</p>
        <div class="actions">
            <a class="button" href="{{ route('lost-items.edit', $item) }}">Edit report</a>
            <a class="button secondary" href="{{ route('lost-items.index') }}">Back to my reports</a>
        </div>
    </article>
@endsection
