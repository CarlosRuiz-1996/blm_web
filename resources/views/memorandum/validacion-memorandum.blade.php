@extends('adminlte::page')


@section('title', 'Validando')

@section('content_header')
<h1 class="ml-2">
  <a href="/boveda/inicio" title="ATRAS">
    <i class="fa fa-arrow-left"></i>
</a>Validacion de Memorándum de servicio en validacion</h1>
@stop
@section('content')
  <livewire:memorandum-validacion.validacion-create :memorandum="$memorandum" :area="$area" :admin="$admin" />

@stop