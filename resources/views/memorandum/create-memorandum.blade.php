@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
<h1 class="ml-2">Memorándum de servicio</h1>
@stop
@section('content')
  <livewire:memorandum-create :cotizacion="$cotizacion" />

@stop