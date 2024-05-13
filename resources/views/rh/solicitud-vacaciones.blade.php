@extends('adminlte::page')


@section('title', 'Usuarios')

@section('content_header')
    <h1 class="ml-3">Recursos Humanos-Solicitud de Vacaciones</h1>
@stop
@section('content')
<x-alert />
    <div class="container-fluid">
        @livewire('rh.solicitud-vacaciones')
    </div>
@stop
