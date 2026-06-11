@extends('admin.layout')
@section('title', 'New event')
@section('heading', 'New event')

@section('content')
    @include('admin.events.form', ['action' => route('admin.events.store'), 'method' => 'POST', 'submit' => 'Create event'])
@endsection
