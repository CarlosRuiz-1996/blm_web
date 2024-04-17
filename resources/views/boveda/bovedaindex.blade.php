@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
<h1 class="ml-2">Boveda</h1>
@stop
@section('content')
@livewire('boveda.index')
@stop
