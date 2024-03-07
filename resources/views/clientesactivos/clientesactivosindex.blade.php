@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
    <h1 class="ml-2">Clientes activos</h1>
@stop
@section('content')
    <div class="container-fluid">
        <div class="form-group mt-0 text-right">
            <a href="{{ route('cliente.create') }}" class="btn btn-info">Nuevo</a>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    {{-- <div class="card-header">
                        <form>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputNumero">Número</label>
                                        <input type="text" class="form-control" id="inputNumero"
                                            placeholder="Ingresa el Numero">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputnombre">Nombre</label>
                                        <input type="text" class="form-control" id="inputnombre"
                                            placeholder="Ingresa el nombre">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputRazonSo">Razón social</label>
                                        <input type="text" class="form-control" id="inputRazonSo"
                                            placeholder="Ingresa Razón social">
                                    </div>
                                </div>

                                <div class="col-md-2 mt-2">
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-info btn-block">Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div> --}}
                  
                    <div class="card-body">
                        {{-- Setup data for datatables --}}
                        @php
                            $heads = ['No.Cliente', 'RFC', 'Razón social', 'Contacto', 'Teléfono', ['label' => 'Acciones', 'no-export' => true, 'width' => 20]];

                            $config = [
                                'language' => ['url' => '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'],
                                'order' => [[0, 'desc']],
                            ];
                        @endphp

                        {{-- Minimal example / fill data using the component slot --}}
                        <x-adminlte-datatable id="table1" :heads="$heads" :config="$config">
                            @foreach ($clientes as $cli)
                                <tr>
                                    <td>
                                        {{ $cli->id }}
                                    </td>
                                    <td>
                                        {{ $cli->rfc_cliente }}
                                    </td>
                                    <td>
                                        {{ $cli->razon_social }}
                                    </td>
                                    <td>
                                        {{ $cli->user->name . ' ' . $cli->user->paterno . ' ' . $cli->user->materno }}
                                    </td>
                                    <td>
                                        {{ $cli->phone }}
                                    </td>
                                  
                                   
                                    <td>
                                        <a href=""
                                            class="btn btn-xs btn-default text-primary mx-1 shadow" title="Nueva cotizacion">
                                            <i class="fa fa-lg fa-fw fa-plus"></i>
                                        </a>
                                        <a href="{{ route('cliente.detalles', $cli) }}"
                                        class="btn btn-xs btn-default text-primary mx-1 shadow" title="Detalles del cliente">
                                        <i class="fa fa-lg fa-fw fa-info-circle"></i>
                                    </a>
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </x-adminlte-datatable>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

