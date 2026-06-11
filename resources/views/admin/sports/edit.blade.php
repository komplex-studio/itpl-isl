@extends('admin.layout')
@section('title', 'Edit sport')
@section('heading', 'Edit '.$sport->name)

@section('content')
    @include('admin.sports.form', ['action' => route('admin.sports.update', $sport), 'method' => 'PUT', 'submit' => 'Save changes'])
@endsection
