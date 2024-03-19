@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
<h1 class="ml-2">Evaluación de Jurdico</h1>
@stop
@section('content')
@livewire('juridico.alta-valida-jurdico', ['expedienteId' => request()->route('id')])
@stop

