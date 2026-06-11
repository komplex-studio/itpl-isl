@extends('admin.layout')
@section('title', 'New fixture')
@section('heading', 'New fixture')

@section('content')
    @include('admin.fixtures.form', ['action' => route('admin.fixtures.store'), 'method' => 'POST', 'submit' => 'Create fixture'])
@endsection
