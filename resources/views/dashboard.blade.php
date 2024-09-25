@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
{{-- <input type="text" class="form-control">
@role('Juridico') el rol debe de escribirse como en la bd
@endrole
@role('OPERADOR') operador
@endrole
@can('menu-juridico') permiso admin-juridico
@endcan
@can('OPERADOR')
operador
@endif
@if(auth()->user()->hasRole('OPERADOR') )
permiso operador 2
@endif --}}
@stop