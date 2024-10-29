@extends('adminlte::page')
@section('title', 'Reporte Clientes')
@section('content_header')
    <h1 class="ml-2">Reporte Clientes</h1>
@stop
@section('content')
<x-alert />
    <div class="container-fluid">
        @livewire('reportegeneral.reporte-general')
    </div>
@stop
