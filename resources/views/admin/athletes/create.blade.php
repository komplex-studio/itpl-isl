@extends('admin.layout')
@section('title', 'New athlete')
@section('heading', 'New athlete')

@section('content')
    @include('admin.athletes.form', ['action' => route('admin.athletes.store'), 'method' => 'POST', 'submit' => 'Add athlete'])
@endsection
