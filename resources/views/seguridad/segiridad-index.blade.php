@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
    <h1 class="ml-2">

        Solicitudes de reporte de factibilidad</h1>
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="solicitud-tab" data-toggle="pill" href="#solicitud"
                                    role="tab" aria-controls="solicitud" aria-selected="true">Solicitudes</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="proceso-tab" data-toggle="pill" href="#proceso" role="tab"
                                    aria-controls="proceso" aria-selected="false">En proceso</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="atendido-tab" data-toggle="pill" href="#atendido" role="tab"
                                    aria-controls="atendido" aria-selected="false">Atendidas</a>
                            </li>
                            <!-- Puedes agregar más pestañas según sea necesario -->
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="solicitud" role="tabpanel"
                                aria-labelledby="solicitud-tab">
                                <div class="row">


                                    @php
                                        $heads = [
                                            ['label' => 'ID Cliente', 'width' => 10],
                                            'Razón social',
                                            'RFC',
                                            'Fecha solicitud',
                                            ['label' => 'Iniciar', 'width' => 20],
                                        ];
                                        $config = [
                                            'language' => [
                                                'url' => '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                                            ],
                                            'order' => [[0, 'desc']],
                                        ];
                                    @endphp

                                    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config"
                                        head-theme="dark" striped hoverable bordered compressed>
                                        @foreach ($solicitudes as $solicitud)
                                        @if($solicitud)
                                            <tr>
                                                <td>{{ $solicitud->id }}</td>
                                                <td>{{ $solicitud->cliente->razon_social }}</td>
                                                <td>{{ $solicitud->cliente->rfc_cliente }}</td>
                                                <td>{{ $solicitud->created_at }}</td>

                                                <td>
                                                    <a href="{{ route('seguridad.reporte', $solicitud->id) }}"
                                                        class="btn btn-primary">
                                                        Iniciar
                                                    </a>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach



                                    </x-adminlte-datatable>


                                </div>
                            </div>

                            <div class="tab-pane fade" id="proceso" role="tabpanel" aria-labelledby="proceso-tab">
                                <div class="row">

                                    @php
                                        $heads = [
                                            'No.',
                                            'Razón social',
                                            'RFC',
                                            'Fecha solicitud',
                                            ['label' => 'Iniciar', 'width' => 20],
                                        ];
                                        $config = [
                                            'language' => [
                                                'url' => '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                                            ],
                                            'order' => [[0, 'desc']],
                                        ];
                                    @endphp

                                    <x-adminlte-datatable id="table2" :heads="$heads" :config="$config"
                                        head-theme="dark" striped hoverable bordered compressed>
                                        @foreach ($procesos as $proceso)
                                            <tr>
                                                <td>{{ $proceso->id }}</td>
                                                <td>{{ $proceso->cliente->razon_social }}</td>
                                                <td>{{ $proceso->cliente->rfc_cliente }}</td>
                                                <td>{{ $proceso->created_at }}</td>

                                                <td>
                                                    <a href="{{ route('seguridad.reporte', $proceso->id) }}"
                                                        class="btn btn-warning">
                                                        Continiar llenando
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </x-adminlte-datatable>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="atendido" role="tabpanel" aria-labelledby="atendido-tab">
                                <div class="row">

                                    @php
                                        $heads = [
                                            'No.',
                                            'Razón social',
                                            'RFC',
                                            'Fecha solicitud',
                                            ['label' => 'Iniciar', 'width' => 20],
                                        ];
                                        $config = [
                                            'language' => [
                                                'url' => '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                                            ],
                                            'order' => [[0, 'desc']],
                                        ];
                                    @endphp

                                    <x-adminlte-datatable id="table3" :heads="$heads" :config="$config"
                                        head-theme="dark" striped hoverable bordered compressed>

                                        @foreach ($terminados as $terminado)
                                            
                                        <tr>
                                            <td>{{ $terminado->id }}</td>
                                            <td>{{ $terminado->cliente->razon_social }}</td>
                                            <td>{{ $terminado->cliente->rfc_cliente }}</td>
                                            <td>{{ $terminado->created_at }}</td>
                                           
                                            <td>
                                                <a href="{{ route('seguridad.reporte', $terminado->id) }}"
                                                    class="btn btn-warning">
                                                    Ver reportes
                                                </a>
                                            </td> 
                                        </tr>
                                @endforeach


                                    </x-adminlte-datatable>
                                </div>
                            </div>
                            <!-- Puedes agregar más contenidos según sea necesario -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
