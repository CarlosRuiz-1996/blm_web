@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <input type="text" class="form-control">
    {{-- @role('Super') el rol debe de escribirse como en la bd
    @endrole
    @can('menu-admin') permiso admin-menu
    @endcan --}}
@stop
