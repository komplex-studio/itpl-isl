@extends('admin.layout')
@section('title', 'New medal row')
@section('heading', 'New state row')

@section('content')
    @include('admin.medal-tallies.form', ['action' => route('admin.medal-tallies.store'), 'method' => 'POST', 'submit' => 'Create row'])
@endsection
