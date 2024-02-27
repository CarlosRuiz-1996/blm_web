@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
<h1 class="ml-2">Alta cliente activo</h1>
@stop
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="text-center">Cliente</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="razonSocial">Razón Social:</label>
                                <input type="text" class="form-control" id="razonSocial"
                                    placeholder="Ingrese la Razón Social" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="rfc">RFC:</label>
                                <input type="text" class="form-control" id="rfc" placeholder="Ingrese el RFC" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="tipoCliente">Tipo de cliente:</label>
                                <select class="form-control" id="tipoCliente">
                                    <option value="1">Opción 1</option>
                                    <option value="2">Opción 2</option>
                                    <option value="3">Opción 3</option>
                                </select>
                            </div>
                        </div>
                       <!-- Información de contacto -->
                       <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="telefono">Teléfono:</label>
                            <input type="number" class="form-control" id="telefono" placeholder="Ingrese el Teléfono"
                                required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="correoElectronico">Correo Electrónico:</label>
                            <input type="email" class="form-control" id="correoElectronico"
                                placeholder="Ingrese el Correo Electrónico" required>
                        </div>
                    </div>
                        <div class="col-md-12 mb-3">
                            <div class="card-header">
                                <h5 class="text-center">Datos del contacto</h5>
                            </div>
                        </div>
                        <hr/>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="nombreContacto">Nombre del contacto:</label>
                                <input type="text" class="form-control" id="nombreContacto"
                                    placeholder="Ingrese el Nombre del Contacto" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="nombreContacto">Apellido Paterno:</label>
                                <input type="text" class="form-control" id="nombreContacto"
                                    placeholder="Ingrese el Nombre del Contacto" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="nombreContacto">Apellido Materno:</label>
                                <input type="text" class="form-control" id="nombreContacto"
                                    placeholder="Ingrese el Nombre del Contacto" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="puesto">Puesto:</label>
                                <input type="text" class="form-control" id="puesto" placeholder="Ingrese el Puesto"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="text-center">Domicilio fiscal</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="estado">Estado:</label>
                                <select class="form-control" id="estado">
                                    <option value="1">Estado 1</option>
                                    <option value="2">Estado 2</option>
                                    <option value="3">Estado 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="municipio">Municipio:</label>
                                <select class="form-control" id="municipio">
                                    <option value="1">municipio 1</option>
                                    <option value="2">municipio 2</option>
                                    <option value="3">municipio 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="cp">Código Postal:</label>
                                <select class="form-control" id="cp">
                                    <option value="1">cp 1</option>
                                    <option value="2">cp 2</option>
                                    <option value="3">cp 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="colonia">Colonia:</label>
                                <input type="text" class="form-control" id="colonia" placeholder="Ingrese la Colonia"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="calleNumero">Calle y Número:</label>
                                <input type="text" class="form-control" id="calleNumero"
                                    placeholder="Ingrese la Calle y Número" required>
                            </div>
                        </div>

                        
                        <div class="col-md-6 mb-3">
                            <button type="submit" class="btn btn-danger btn-block">Cancelar</button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button type="submit" class="btn btn-info btn-block">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    console.log('Hi!'); 
</script>
@stop