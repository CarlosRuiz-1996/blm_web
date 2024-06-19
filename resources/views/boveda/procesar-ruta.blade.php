@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')

<h1 class="ml-2">
    <a href="{{route('boveda.index')}}" title="ATRAS">
        <i class="fa fa-arrow-left"></i>
    </a>
    Procesar ruta {{$ruta->nombre->name}} del {{$ruta->dia->name}}
</h1>
@stop
@section('content')


<livewire:boveda.ruta-procesar :ruta='$ruta' />
@stop