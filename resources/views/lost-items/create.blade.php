@extends('layouts.app')

@section('title', 'Report a Lost Item')

@section('content')
    <h1>Report a lost item</h1>
    <p class="muted">Add a few details so the team can help reunite you with your item.</p>
    <form class="card" method="POST" action="{{ route('lost-items.store') }}">
        @csrf
        @include('lost-items.partials.form', ['submitLabel' => 'Submit report'])
    </form>
@endsection
