@extends('layouts.main')

@section('body-classes')
    nav-md
@endsection

@section('content-header')
    @include('partials.main-header')
@endsection

@section('content')
    @include('partials.sidebar')
    @yield('content')
@overwrite