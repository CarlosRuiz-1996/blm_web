@extends('adminlte::page')


@section('title', 'Expediente digital')

@section('content_header')
    <h1 class="ml-2">
        <a href="{{ route('cliente.detalles', [$cliente, 2]) }}" title="ATRAS">
            <i class="fa fa-arrow-left"></i>
        </a>
        @if ($sts == 0)
            Solicitud de cumplimiento
        @else
            Expediente digital
        @endif
    </h1>

@stop
@section('content')
    @livewire('expediente.gestion-documentos')
@stop
