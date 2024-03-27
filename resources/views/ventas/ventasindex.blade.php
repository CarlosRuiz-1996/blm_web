@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
<h1 class="ml-2">Ventas</h1>
@stop
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                                href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                                aria-selected="true">COTIZACIONES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-servicios-tab" data-toggle="pill"
                                href="#custom-tabs-one-servicios" role="tab" aria-controls="custom-tabs-one-servicios"
                                aria-selected="false">ANEXO 1</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-facturacion-tab" data-toggle="pill"
                                href="#custom-tabs-one-facturacion" role="tab"
                                aria-controls="custom-tabs-one-facturacion" aria-selected="false">MEMORÁNDUM</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-cotizacion-tab" data-toggle="pill"
                                href="#custom-tabs-one-cotizacion" role="tab" aria-controls="custom-tabs-one-cotizacion"
                                aria-selected="false">VALIDACIÓN MEMORÁNDUM</a>
                        </li>
                        <!-- Puedes agregar más pestañas según sea necesario -->
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                            aria-labelledby="custom-tabs-one-home-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 for="">Cotizaciones</h3>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#exampleModalCenter">
                                            Nuevo
                                        </button>
                                    </div>
                                </div>
                                @livewire('cotizaciones-index-tabla')
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-servicios" role="tabpanel"
                            aria-labelledby="custom-tabs-one-servicios-tab">
                            
                            <livewire:anexo1.listados-anexo1 />
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-facturacion" role="tabpanel"
                            aria-labelledby="custom-tabs-one-facturacion-tab">
                           <livewire:memorandum.memorandum-listados />
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-cotizacion" role="tabpanel"
                            aria-labelledby="custom-tabs-one-cotizacion-tab">
                            <livewire:memorandum-validacion.validacion-listados :area="1"/>
                           

                        </div>
                        <!-- Puedes agregar más contenidos según sea necesario -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@livewire('modal-cotizacion-nuevo')
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
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
<script>
    console.log('Hi!'); 
</script>
@stop