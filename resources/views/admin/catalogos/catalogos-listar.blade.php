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
    @endif
@stop
