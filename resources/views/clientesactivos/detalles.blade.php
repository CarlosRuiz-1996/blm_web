@extends('adminlte::page')


@section('title', 'Cliente')

@section('content_header')
    <h1 class="ml-2">
        <a href="{{ $op == 1 ? route('cliente.index') : '/ventas' }}" title="ATRAS">
            <i class="fa fa-arrow-left"></i>
        </a>
        Detalles del cliente
    </h1>
@stop
@section('content')
    <div class="container-fluid">

        {{-- informacion del cliente --}}
        <div class="row">
            <div class="col-md-8">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        <h5><b>Razón Social: </b>{{ $cliente->razon_social }}.</h5>
                        <h5><b>Contacto:
                            </b>{{ $cliente->user->name . ' ' . $cliente->user->paterno . ' ' . $cliente->user->materno }}.
                        </h5>
                        <h5><b>RFC: </b>{{ $cliente->rfc_cliente }}.</h5>
                        <h5>
                            <b>Dirección: </b>
                            {{ $direccion_completa }}.
                        </h5>
                        <h5><b>Telefono: </b>{{ $cliente->phone }}.</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-outline card-info">
                    <div class="card-body text-end w-100">
                        <div class="row">
                            <div class="col-md-12 mb-3 text-center">
                                <h4 class="text-end">Estatus
                                    <i class="fa fa-circle"
                                        title="{{ $cliente->status_cliente == 1 ? 'Activo' : 'Proceso' }} "
                                        style="color:{{ $cliente->status_cliente == 1 ? 'green' : 'orange' }} "
                                        aria-hidden="true"></i>
                                </h4>
                            </div>
                            <div class="col-md-12 mb-3">
                                <a class="btn btn-primary btn-block">Nueva cotización</a>
                            </div>
                            <div class="col-md-12 mb-3">
                                <a href="{{ route('cliente.edit', $cliente->id) }}" class="btn btn-info btn-block">Editar
                                    cliente</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                                    href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                                    aria-selected="true">EXPEDIENTE DIGITAL</a>
                            </li>
                            @if ($cliente->status_cliente == 1)
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-servicios-tab" data-toggle="pill"
                                        href="#custom-tabs-one-servicios" role="tab"
                                        aria-controls="custom-tabs-one-servicios" aria-selected="false">SERVICIOS</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-facturacion-tab" data-toggle="pill"
                                        href="#custom-tabs-one-facturacion" role="tab"
                                        aria-controls="custom-tabs-one-facturacion" aria-selected="false">FACTURACIÓN</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-boveda-tab" data-toggle="pill"
                                        href="#custom-tabs-one-boveda" role="tab" aria-controls="custom-tabs-one-boveda"
                                        aria-selected="false">RESGUARDO BOVEDA</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-cotizacion-tab" data-toggle="pill"
                                    href="#custom-tabs-one-cotizacion" role="tab"
                                    aria-controls="custom-tabs-one-cotizacion" aria-selected="false">COTIZACIONES</a>
                            </li>

                            <!-- Puedes agregar más pestañas según sea necesario -->
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                                aria-labelledby="custom-tabs-one-home-tab">


                                @livewire('tabla-documentos', ['cliente' => $cliente, 'cliente_status'=>$cliente->status_cliente])
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-one-servicios" role="tabpanel"
                                aria-labelledby="custom-tabs-one-servicios-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3 for="">Servicios</h3>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 d-flex justify-content-end">
                                            <button class="btn btn-primary">Agregar Servicios</button>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th>Servicio</th>
                                                        <th>Total</th>
                                                        <th>Eliminar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-one-facturacion" role="tabpanel"
                                aria-labelledby="custom-tabs-one-facturacion-tab">
                                <!-- Contenido de la segunda pestaña -->
                                Contenido de la Tab 3
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-one-cotizacion" role="tabpanel"
                                aria-labelledby="custom-tabs-one-cotizacion-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-outline card-info">
                                            <div class="card-header">
                                                <form>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="inputId">Id</label>
                                                                <input type="text" class="form-control" id="inputId"
                                                                    placeholder="Ingresa la Id">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="inputFechaInicio">Fecha Inicio</label>
                                                                <input type="date" class="form-control"
                                                                    id="inputFechaInicio"
                                                                    placeholder="Ingresa el Fecha Inicio">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="inputFechafin">Fecha Fin</label>
                                                                <input type="date" class="form-control"
                                                                    id="inputFechafin" placeholder="Ingresa Fecha fin">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputestatus">Estatus</label>
                                                                <select class="form-control" id="inputestatus">
                                                                    <option value="">Seleccionar</option>
                                                                    <option value="opcion1">Opción 1</option>
                                                                    <option value="opcion2">Opción 2</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mt-2">
                                                            <div class="form-group mt-4">
                                                                <button type="submit"
                                                                    class="btn btn-info btn-block">Buscar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead class="table-primary">
                                                            <tr>
                                                                <th>Id</th>
                                                                <th>Fecha</th>
                                                                <th>Estatus</th>
                                                                <th>Total</th>
                                                                <th>Contratacion</th>
                                                                <th>Exportar</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Aquí irán los datos de tu tabla -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-one-boveda" role="tabpanel"
                                aria-labelledby="custom-tabs-one-boveda-tab">
                                <h3 class="text-center">Resguardo total</h3>
                                <canvas id="myChart" style="width:100%;"></canvas>
                            </div>
                            <!-- Puedes agregar más contenidos según sea necesario -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@stop



@section('js')
    <script>
        // Obtener el contexto del lienzo (canvas)
        var ctx = document.getElementById('myChart').getContext('2d');

        // Datos del gráfico
        var data = {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo'],
            datasets: [{
                label: 'Ventas Mensuales',
                data: [12, 19, 3, 5, 2],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        // Opciones del gráfico
        var options = {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            responsive: true // Mantén la responsividad activada
        };

        // Crear el gráfico
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        });
    </script>

@stop
