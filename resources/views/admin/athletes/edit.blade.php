@extends('admin.layout')
@section('title', 'Edit athlete')
@section('heading', 'Edit '.$athlete->name)

@section('content')
    @include('admin.athletes.form', ['action' => route('admin.athletes.update', $athlete), 'method' => 'PUT', 'submit' => 'Save changes'])
@endsection
