@extends('adminlte::page')


@section('title', 'Nuevo usuario')

@section('content_header')
    <h1 class="ml-2">Crear Usuario Nuevos</h1>
@stop
@section('content')
<style>
    .colorrojo{
        color: red;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header text-center" >
                    <h3>Datos usuario</h3>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="inputNombre">Nombre <label class="colorrojo">*</label></label>
                                    <input type="text" class="form-control" id="inputNombre" required placeholder="Ingrese su nombre">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="inputApellidoPaterno">Apellido Paterno <label class="colorrojo">*</label></label>
                                    <input type="text" class="form-control" id="inputApellidoPaterno" required placeholder="Ingrese su apellido paterno">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="inputApellidoMaterno">Apellido Materno <label class="colorrojo">*</label></label>
                                    <input type="text" class="form-control" id="inputApellidoMaterno" required placeholder="Ingrese su apellido materno">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="inputEmail">Correo Electrónico <label class="colorrojo">*</label></label>
                                    <input type="email" class="form-control" id="inputEmail" required placeholder="Ingrese su correo electrónico">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="inputPassword">Contraseña <label class="colorrojo">*</label></label>
                                    <input type="password" class="form-control" id="inputPassword" required placeholder="Ingrese su contraseña">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="inputConfirmPassword">Confirmar Contraseña <label class="colorrojo">*</label></label>
                                    <input type="password" class="form-control" id="inputConfirmPassword" required placeholder="Confirme su contraseña">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="selectArea">Área <label class="colorrojo">*</label></label>
                                    <select class="form-control" id="selectArea">
                                        <option value="">Seleccionar</option>
                                        <option value="opcion1">Opción 1</option>
                                        <option value="opcion2">Opción 2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="selectTipoCuenta">Tipo de cuenta <label class="colorrojo">*</label></label>
                                    <select class="form-control" id="selectTipoCuenta">
                                        <option value="">Seleccionar</option>
                                        <option value="opcion1">Opción 1</option>
                                        <option value="opcion2">Opción 2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button type="submit" class="btn btn-danger btn-block">Cancelar</button>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button type="submit" class="btn btn-info btn-block">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
                
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop