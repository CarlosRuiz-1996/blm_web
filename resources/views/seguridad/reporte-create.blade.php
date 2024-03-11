@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
<h1 class="ml-2">
    <a href="{{ route('seguridad.index') }}" title="ATRAS">
        <i class="fa fa-arrow-left"></i>
    </a>
    Evaluacíon de riesgo</h1>
@stop
@section('content')
    <livewire:factibilidad :anexo="$anexo" />

@stop