@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
<h1 class="ml-2">
    <a href="{{route('juridico.index')}}" title="ATRAS">
        <i class="fa fa-arrow-left"></i>
    </a>
    Evaluación de Juridico
</h1>
@stop
@section('content')
@livewire('juridico.alta-valida-jurdico', ['expedienteId' => request()->route('id')])
@stop

