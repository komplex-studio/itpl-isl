@extends('admin.layout')
@section('title', 'Edit certificate')
@section('heading', 'Edit certificate')

@section('content')
    @include('admin.certificates.form', ['action' => route('admin.certificates.update', $certificate), 'method' => 'PUT', 'submit' => 'Save changes'])
@endsection
