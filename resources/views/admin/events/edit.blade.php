@extends('admin.layout')
@section('title', 'Edit event')
@section('heading', 'Edit '.$event->name)

@section('content')
    @include('admin.events.form', ['action' => route('admin.events.update', $event), 'method' => 'PUT', 'submit' => 'Save changes'])
@endsection
