@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
    <h1 class="ml-2">Clientes activos</h1>
@stop
@section('content')
<div class="container-fluid">
    <div class="form-group mt-0 text-right">
        <button type="submit" class="btn btn-info">Nuevo</button>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header" >
                    <form>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputNumero">Número</label>
                                    <input type="text" class="form-control" id="inputNumero" placeholder="Ingresa el Numero">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputnombre">Nombre</label>
                                    <input type="text" class="form-control" id="inputnombre" placeholder="Ingresa el nombre">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputRazonSo">Razón social</label>
                                    <input type="text" class="form-control" id="inputRazonSo" placeholder="Ingresa Razón social">
                                </div>
                            </div>

                            <div class="col-md-2 mt-2">
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
                                <th>No.Cliente</th>
                                <th>RFC</th>
                                <th>Razón social</th>
                                <th>Contacto</th>
                                <th>Teléfono</th>
                                <th>Agregar servicio</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>   
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
    <script> console.log('Hi!'); </script>
@stop