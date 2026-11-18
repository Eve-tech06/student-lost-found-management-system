@extends('layouts.app')

@section('title', 'My Lost Items')

@section('content')
    <div class="actions" style="justify-content: space-between; align-items: center">
        <div><h1>My lost items</h1><p class="muted">Reports you have submitted.</p></div>
        <a class="button" href="{{ route('lost-items.create') }}">Report an item</a>
    </div>

    @forelse ($items as $item)
        <article class="card">
            <h2><a href="{{ route('lost-items.show', $item) }}">{{ $item->item_name }}</a></h2>
            <p class="muted">{{ $item->category->category_name }} · {{ $item->location->location_name }} · {{ $item->date_lost_found->format('M j, Y') }}</p>
            <p>Status: {{ ucfirst($item->status) }}</p>
            @if ($item->description)<p>{{ $item->description }}</p>@endif
            <a href="{{ route('lost-items.show', $item) }}">View details</a>
        </article>
    @empty
        <div class="card">
            <p>You haven’t reported any lost items yet.</p>
            <a class="button" href="{{ route('lost-items.create') }}">Report your first item</a>
        </div>
    @endforelse

    {{ $items->links() }}
@endsection
