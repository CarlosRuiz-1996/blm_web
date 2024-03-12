@extends('adminlte::page')


@section('title', 'Cumplimiento')

@section('content_header')
<h1 class="ml-2">Cumplimiento</h1>
@stop
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">SOLICITUDES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-servicios-tab" data-toggle="pill" href="#custom-tabs-one-servicios" role="tab" aria-controls="custom-tabs-one-servicios" aria-selected="false">EN PROCESO</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-facturacion-tab" data-toggle="pill" href="#custom-tabs-one-facturacion" role="tab" aria-controls="custom-tabs-one-facturacion" aria-selected="false">ATENDIDAS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-cotizacion-tab" data-toggle="pill" href="#custom-tabs-one-cotizacion" role="tab" aria-controls="custom-tabs-one-cotizacion" aria-selected="false">MEMORÁNDUM</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                            <!--livewire tabla solicitudes-->
                            @livewire('tabla-cumplimiento-solicitud')
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-servicios" role="tabpanel" aria-labelledby="custom-tabs-one-servicios-tab">
                            @livewire('tabla-cumplimiento-en-proceso')
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-facturacion" role="tabpanel" aria-labelledby="custom-tabs-one-facturacion-tab">
                            @livewire('tabla-cumplimiento-atendidas')
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-cotizacion" role="tabpanel" aria-labelledby="custom-tabs-one-cotizacion-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-outline card-info">
                                        <div class="card-header" >
                                            <form>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="inputId">Id</label>
                                                            <input type="text" class="form-control" id="inputId" placeholder="Ingresa la Id">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="inputFechaInicio">Fecha Inicio</label>
                                                            <input type="date" class="form-control" id="inputFechaInicio" placeholder="Ingresa el Fecha Inicio">
                                                        </div>
                                                    </div>
                        
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="inputFechafin">Fecha Fin</label>
                                                            <input type="date" class="form-control" id="inputFechafin" placeholder="Ingresa Fecha fin">
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
                                                            <button type="submit" class="btn btn-info btn-block">Buscar</button>
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