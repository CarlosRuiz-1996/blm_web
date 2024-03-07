@extends('adminlte::page')


@section('title', 'Anexo 1')

@section('content_header')
    <h1 class="ml-2">Anexo 1</h1>
@stop
@section('content')
    @livewire('anexo1',['cotizacion' => $cotizacion]   )
@stop