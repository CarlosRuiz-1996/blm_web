@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
    <div class="d-sm-flex align-items-center justify-content-between">

        <h1 class="ml-2">Operaciones</h1>
        <a href="{{ route('ruta.gestion',1) }}" class="btn btn-primary">
            Nueva Ruta
            <i class="fa fa-plus" aria-hidden="true"></i>
        </a>

    </div>

@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link active" id="rutas-tab" data-toggle="pill"
                                    href="#rutas" role="tab"
                                    aria-controls="rutas" aria-selected="false">RUTAS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="validacion-memo-tab" data-toggle="pill"
                                    href="#validacion-memo" role="tab"
                                    aria-controls="validacion-memo" aria-selected="false">VALIDACIÓN
                                    MEMORANDUM</a>
                            </li>
                            
                            <!-- Puedes agregar más pestañas según sea necesario -->
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">

                            <div class="tab-pane fade show active" id="rutas" role="tabpanel"
                                aria-labelledby="rutas-tab">
                                
                                <livewire:operaciones.ruta-list />

                            </div>
                            <div class="tab-pane fade" id="validacion-memo" role="tabpanel"
                                aria-labelledby="validacion-memo-tab">
                                <livewire:memorandum-validacion.validacion-listados :area="2" />


                            </div>
                            <!-- Puedes agregar más contenidos según sea necesario -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
