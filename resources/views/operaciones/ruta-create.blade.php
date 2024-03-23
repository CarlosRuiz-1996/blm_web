@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
    <div class="d-sm-flex align-items-center justify-content-between">
        <h1>
            <a href="/operaciones" title="ATRAS">
                <i class="fa fa-arrow-left"></i>
            </a>
            {{ $op == 1 ? 'Nueva Ruta' : 'Editar Ruta' }}
        </h1>

       
        <livewire:operaciones.ruta-ctg-ruta />
    @stop
    @section('content')
        <livewire:operaciones.ruta-formulario :op="$op" :ruta="$ruta" />

    @stop
