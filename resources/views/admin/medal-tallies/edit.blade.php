@extends('admin.layout')
@section('title', 'Edit medal row')
@section('heading', 'Edit '.$tally->state)

@section('content')
    @include('admin.medal-tallies.form', ['action' => route('admin.medal-tallies.update', $tally), 'method' => 'PUT', 'submit' => 'Save changes'])
@endsection
