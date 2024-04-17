@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
@stop
@section('content')
@livewire('juridico.alta-valida-jurdico', ['expedienteId' => request()->route('id')])
@stop

