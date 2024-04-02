@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
<h1 class="ml-2">
    <a href="{{route('ventas.indexventas')}}" title="ATRAS">
        <i class="fa fa-arrow-left"></i>
    </a>
    Cotizacion
</h1>
@stop
@section('content')
@livewire('crear-tabla-cotizacion')
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop