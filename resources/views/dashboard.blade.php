@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @if(auth()->user()->role === 'repartidor')
        @include('dashboard.repartidor')
    @else
        @include('dashboard.administrativo')
    @endif
@endsection
