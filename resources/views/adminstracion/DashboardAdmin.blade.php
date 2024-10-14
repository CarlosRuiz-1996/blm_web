@extends('adminlte::page')


@section('title', 'Administración tablero')
@section('content_header')
<h1 class="ml-2">Administración tablero</h1>
@stop
@section('content')
<livewire:administracion.DashboardAdmin />
@stop