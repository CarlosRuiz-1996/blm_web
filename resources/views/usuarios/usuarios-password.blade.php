@extends('adminlte::page')


@section('title', 'Restablecer contraseña')

@section('content_header')
    <h1 class="ml-2">Cambiar Contraseña</h1>
@stop
@section('content')
    <style>
        .colorrojo {
            color: red;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    
                    <div class="card-body">
                        <form action="{{ route('user.save-password',$user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="password">Nueva contraseña <label class="colorrojo">*</label></label>
                                        <input type="password" class="form-control" name="password" id="password" required
                                            placeholder="Ingrese la contraseña">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="password_confirm">Confirmar nueva Contraseña <label
                                                class="colorrojo">*</label></label>
                                        <input type="password" class="form-control" id="password_confirm" required
                                            placeholder="Confirme la contraseña">
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <a href="{{ route('user.index') }}" class="btn btn-danger btn-block">Cancelar</a>
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

    
