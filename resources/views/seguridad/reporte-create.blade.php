@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
<h1 class="ml-2">Evaluacíon de riesgo</h1>
@stop
@section('content')
    <livewire:factibilidad :cliente="$cliente" />

@stop