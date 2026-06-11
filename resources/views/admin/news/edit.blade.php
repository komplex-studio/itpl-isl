@extends('admin.layout')
@section('title', 'Edit article')
@section('heading', 'Edit article')

@section('content')
    @include('admin.news.form', ['action' => route('admin.news.update', $article), 'method' => 'PUT', 'submit' => 'Save changes'])
@endsection
