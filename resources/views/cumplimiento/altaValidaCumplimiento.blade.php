@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
<h1 class="ml-2">Evaluación de Cumplimiento</h1>
@stop
@section('content')
@livewire('alta-valida-cumplimiento', ['expedienteId' => request()->route('id')])
@stop

