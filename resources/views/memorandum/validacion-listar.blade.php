@extends('adminlte::page')


@section('title', 'Validando')

@section('content_header')
<h1 class="ml-2">
  
@stop
@section('content')
  <livewire:memorandum-validacion.validacion-listados :area="$area" :name="$name"/>

@stop