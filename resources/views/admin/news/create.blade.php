@extends('admin.layout')
@section('title', 'New article')
@section('heading', 'New article')

@section('content')
    @include('admin.news.form', ['action' => route('admin.news.store'), 'method' => 'POST', 'submit' => 'Publish article'])
@endsection
