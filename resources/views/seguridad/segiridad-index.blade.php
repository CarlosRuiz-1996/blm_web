@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
<h1 class="ml-2">Solicitudes de reporte de factibilidad</h1>
<a href="{{route('seguridad.reporte','1')}}">aaaa</a>
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

                                <div class="col-md-12">
                                    <div class="card card-outline card-info">
                                        <div class="card-header">
                                            <form>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="inputId">ID</label>
                                                            <input type="text" class="form-control" id="inputId"
                                                                placeholder="Ingresa la ID">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="inputFechaInicio">RFC</label>
                                                            <input type="text" class="form-control"
                                                                id="inputFechaInicio" placeholder="RFC">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="inputFechafin">Fecha solicitud</label>
                                                            <input type="date" class="form-control" id="inputFechafin"
                                                                placeholder="Ingresa Fecha solicitud">
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
                                                            <th>No.</th>
                                                            <th>Razón social</th>
                                                            <th>RFC</th>
                                                            <th>Contacto</th>
                                                            <th>Fecha solicitud</th>
                                                            <th>Iniciar</th>
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

                        <div class="tab-pane fade" id="proceso" role="tabpanel" aria-labelledby="proceso-tab">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="card card-outline card-info">
                                        <div class="card-header">
                                            <form>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="inputId">ID</label>
                                                            <input type="text" class="form-control" id="inputId"
                                                                placeholder="Ingresa la ID">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="inputFechaInicio">RFC</label>
                                                            <input type="text" class="form-control"
                                                                id="inputFechaInicio" placeholder="RFC">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="inputFechafin">Fecha solicitud</label>
                                                            <input type="date" class="form-control" id="inputFechafin"
                                                                placeholder="Ingresa Fecha solicitud">
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
                                                            <th>No.</th>
                                                            <th>Razón social</th>
                                                            <th>RFC</th>
                                                            <th>Contacto</th>
                                                            <th>Fecha solicitud</th>
                                                            <th>Iniciar</th>
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
                        <div class="tab-pane fade" id="atendido" role="tabpanel" aria-labelledby="atendido-tab">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="card card-outline card-info">
                                        <div class="card-header">
                                            <form>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="inputId">ID</label>
                                                            <input type="text" class="form-control" id="inputId"
                                                                placeholder="Ingresa la ID">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="inputFechaInicio">RFC</label>
                                                            <input type="text" class="form-control"
                                                                id="inputFechaInicio" placeholder="RFC">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="inputFechafin">Fecha solicitud</label>
                                                            <input type="date" class="form-control" id="inputFechafin"
                                                                placeholder="Ingresa Fecha solicitud">
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
                                                            <th>No.</th>
                                                            <th>Razón social</th>
                                                            <th>RFC</th>
                                                            <th>Contacto</th>
                                                            <th>Fecha solicitud</th>
                                                            <th>Iniciar</th>
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
                        <!-- Puedes agregar más contenidos según sea necesario -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop