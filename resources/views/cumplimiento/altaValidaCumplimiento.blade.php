@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
<h1 class="ml-2">
    <a href="{{route('cumplimiento.index')}}" title="ATRAS">
        <i class="fa fa-arrow-left"></i>
    </a>
    Evaluación de Cumplimiento
</h1>
@stop
@section('content')
@livewire('cumplimiento.gestion-cumplimiento', ['expedienteId' => request()->route('id')])
@stop

