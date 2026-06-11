@extends('admin.layout')
@section('title', 'Edit fixture')
@section('heading', 'Edit fixture')

@section('content')
    @include('admin.fixtures.form', ['action' => route('admin.fixtures.update', $fixture), 'method' => 'PUT', 'submit' => 'Save changes'])
@endsection
