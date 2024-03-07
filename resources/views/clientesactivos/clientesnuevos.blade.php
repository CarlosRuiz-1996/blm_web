@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
    <h1 class="ml-2">Alta cliente activo</h1>
@stop
@section('content')
    @livewire('cliente-activo-formulario')
@stop

