@extends('adminlte::page')


@section('title', 'Catalogos')

@section('content_header')
    {{-- <h1>Listado de Catalogos</h1> --}}

@stop

@section('content')
    @if ($op == 1)
        <livewire:catalogos.vehiculos />
    @elseif($op == 2)
        <livewire:catalogos.vehiculos-marcas />
    @elseif($op == 3)
        <livewire:catalogos.vehiculos-modelos />
    @elseif($op == 4)
        <livewire:catalogos.rutas-estados />
    @elseif($op == 5)
        <livewire:catalogos.rutas-nombres />
    @elseif($op == 6)
        <livewire:catalogos.rutas-riesgos />
    @elseif($op == 7)
        <livewire:catalogos.rutas-dias />
    @endif
@stop
