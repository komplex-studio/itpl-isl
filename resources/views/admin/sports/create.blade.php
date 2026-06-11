@extends('admin.layout')
@section('title', 'New sport')
@section('heading', 'New sport')

@section('content')
    @include('admin.sports.form', ['action' => route('admin.sports.store'), 'method' => 'POST', 'submit' => 'Create sport'])
@endsection
