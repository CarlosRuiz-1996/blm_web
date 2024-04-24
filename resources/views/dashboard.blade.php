@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <input type="text" class="form-control">
    @role('Juridico') el rol debe de escribirse como en la bd
    @endrole

    @can('menu-juridico') permiso admin-juridico
    @endcan

    @if(auth()->user()->can('menu-juridico') && $some_other_condition)
  permiso admin-juridico2
@endif
@stop

