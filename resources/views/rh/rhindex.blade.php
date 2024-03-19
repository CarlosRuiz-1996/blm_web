@extends('adminlte::page')


@section('title', 'Usuarios')

@section('content_header')
    <h1 class="ml-2">Recursos Humanos</h1>
@stop
@section('content')
<x-alert />
    <div class="container-fluid">
        @livewire('rh.home-rh')
    </div>
@stop
