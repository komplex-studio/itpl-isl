@extends('admin.layout')
@section('title', 'New certificate')
@section('heading', 'Issue certificate')

@section('content')
    @include('admin.certificates.form', ['action' => route('admin.certificates.store'), 'method' => 'POST', 'submit' => 'Issue certificate'])
@endsection
