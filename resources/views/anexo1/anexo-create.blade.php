@extends('adminlte::page')


@section('title', 'Anexo 1')

@section('content_header')
    <h1 class="ml-2">
        <a href="/ventas" title="ATRAS">
            <i class="fa fa-arrow-left"></i>
        </a>
        Anexo 1
    </h1>

@stop
@section('content')
    <livewire:anexo1.gestion-anexo1 :cotizacion=$cotizacion />

@stop
