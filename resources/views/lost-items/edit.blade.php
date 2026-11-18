@extends('layouts.app')

@section('title', 'Edit Lost Item')

@section('content')
    <h1>Edit lost item report</h1>
    <form class="card" method="POST" action="{{ route('lost-items.update', $item) }}">
        @csrf
        @method('PUT')
        @include('lost-items.partials.form', ['submitLabel' => 'Save changes'])
    </form>
@endsection
