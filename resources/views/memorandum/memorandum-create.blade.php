@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
<h1 class="ml-2">
  <a href="/ventas" title="ATRAS">
    <i class="fa fa-arrow-left"></i>
</a>Memorándum de servicio</h1>
@stop
@section('content')
  <livewire:memorandum.memorandum-gestion :factibilidad="$factibilidad" />

@stop