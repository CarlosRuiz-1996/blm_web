@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
    <h1 class="ml-2">Listado de usuarios</h1>
@stop
@section('content')
@livewire('crear-usuario');

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
                                    <label for="inputcuenta">Cuenta</label>
                                    <input type="text" class="form-control" id="inputcuenta" placeholder="Ingresa la cuenta">
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
                                    <label for="inputarea">Área</label>
                                    <input type="text" class="form-control" id="inputarea" placeholder="Ingresa el área">
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
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Cuenta</th>
                                <th>Alta</th>
                                <th>Área</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
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
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop