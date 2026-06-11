@extends('admin.layout')
@section('title', 'Edit registration')
@section('heading', 'Edit registration')

@section('content')
    @include('admin.registrations.form', ['action' => route('admin.registrations.update', $registration), 'method' => 'PUT', 'submit' => 'Save changes'])
@endsection
