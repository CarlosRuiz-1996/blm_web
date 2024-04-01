@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
    <h1 class="ml-2">
        <a href="{{route('cliente.index')}}" title="ATRAS">
            <i class="fa fa-arrow-left"></i>
        </a>
        Crear cliente
    </h1>
@stop
@section('content')
    @livewire('cliente-activo-formulario')
@stop

