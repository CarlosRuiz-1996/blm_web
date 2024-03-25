@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
    <div class="d-sm-flex align-items-center justify-content-between">
        <h1>
            <a href="/operaciones" title="ATRAS">
                <i class="fa fa-arrow-left"></i>
            </a>
            {{ $op == 1 ? 'Nueva Ruta' : 'Gestión de Ruta' }}
        </h1>

        @if($op == 1)
        <livewire:operaciones.rutas.ruta-ctg-ruta />
        @endif
    </div>
@stop
@section('content')
    <livewire:operaciones.ruta-gestion :ruta="$ruta" />

@stop
