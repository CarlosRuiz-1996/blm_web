@extends('adminlte::page')
{{-- implementa la plantilla admin lte3 --}}
@section('title', 'Operadores')


@section('content')

{{-- 
renderiza el componente de livewire 
dentro de la carpeta de resourse/view/livewirwe/operadores/...files
--}}
@livewire('operadores.operadores-index')
@stop
