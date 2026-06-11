@extends('admin.layout')
@section('title', 'New registration')
@section('heading', 'New registration')

@section('content')
    @include('admin.registrations.form', ['action' => route('admin.registrations.store'), 'method' => 'POST', 'submit' => 'Create registration'])
@endsection
