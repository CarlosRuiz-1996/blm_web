@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
<h1 class="ml-2">Boveda</h1>
@stop
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                      
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-cotizacion-tab" data-toggle="pill"
                                href="#custom-tabs-one-cotizacion" role="tab" aria-controls="custom-tabs-one-cotizacion"
                                aria-selected="false">VALIDACIÓN MEMORANDUM</a>
                        </li>
                        <!-- Puedes agregar más pestañas según sea necesario -->
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        
                        <div class="tab-pane fade show active" id="custom-tabs-one-cotizacion" role="tabpanel"
                            aria-labelledby="custom-tabs-one-cotizacion-tab">
                            <livewire:memorandum-validacion.validacion-listados :area="3"/>
                           

                        </div>
                        <!-- Puedes agregar más contenidos según sea necesario -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop