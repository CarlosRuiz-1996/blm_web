@extends('adminlte::page')


@section('title', 'Validando')

@section('content_header')
<h1 class="ml-2">
  <a href="/ventas" title="ATRAS">
    <i class="fa fa-arrow-left"></i>
</a>Memorándum de servicio en validacion</h1>
@stop
@section('content')
  <livewire:memorandum.memorandum-validando :memorandum="$memorandum" />

@stop