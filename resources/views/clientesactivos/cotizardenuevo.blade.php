@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
<h1 class="ml-2">Cotizacion No.67</h1>
@stop
@section('content')
@livewire('cotizacion-nuevo')
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    console.log('Hi!'); 
</script>
@stop