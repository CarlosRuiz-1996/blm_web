@extends('adminlte::page')


@section('title', 'Editar cliente')

@section('content_header')
    <h1 class="ml-2">Editar cliente activo</h1>
@stop
@section('content')
    @livewire('cliente-activo-formulario',['cliente' => $cliente]   )
@stop