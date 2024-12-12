@extends('adminlte::page')


@section('title', 'Catalogos')


{{-- vista en cargada de renderizar un comonente de livewire dependiendo de que opcion le pasen por la url --}}
@section('content')
    @if ($op == 1)
        <livewire:catalogos.vehiculos />
    @elseif($op == 2)
        <livewire:catalogos.vehiculos-marcas />
    @elseif($op == 3)
        <livewire:catalogos.vehiculos-modelos />
    @elseif($op == 4)
        <livewire:catalogos.rutas-estados />
    @elseif($op == 5)
        <livewire:catalogos.rutas-nombres />
    @elseif($op == 6)
        <livewire:catalogos.rutas-riesgos />
    @elseif($op == 7)
        <livewire:catalogos.rutas-dias />
    @elseif($op == 8)
        <livewire:catalogos.contratos />
    @elseif($op == 9)
        <livewire:catalogos.areas />
    @elseif($op == 10)
        <livewire:catalogos.consignatario />
    @elseif($op == 11)
        <livewire:catalogos.dia-entregas />
    @elseif($op == 12)
        <livewire:catalogos.dia-servicio />
    @elseif($op == 13)
        <livewire:catalogos.horario-servicio />
    @elseif($op == 14)
        <livewire:catalogos.horario-entrega />
    @elseif($op == 15)
        <livewire:catalogos.tipo-cliente />
    @elseif($op == 16)
        <livewire:catalogos.tipo-solicitud />
    @elseif($op == 17)
        <livewire:catalogos.tipo-pago />
    @elseif($op == 18)
        <livewire:catalogos.tipo-servicio />
    @elseif($op == 19)
        <livewire:catalogos.tipo-moneda />
    @elseif($op == 20)
        <livewire:catalogos.servicios />
    @endif
@stop
