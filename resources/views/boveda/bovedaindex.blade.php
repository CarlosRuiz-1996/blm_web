@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
<h1 class="ml-2">Boveda</h1>
@stop
@section('content')
<div class="container-fluid">
    <div class="info-box">
        <span class="info-box-icon bg-info"><i class="fas fa-dollar-sign"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Resguardo</span>
            <span class="info-box-number">$41,410</span>
            <div class="progress">
                <div class="progress-bar bg-info" style="width: 70%"></div>
            </div>
            <span class="progress-description">
                Aumento del 70% en 30 días
            </span>
        </div>
    </div>  
    <div class="info-box">
        <div class="info-box-content">
        <div class="table-responsive">
            <table class="table">
                <thead class="table-info">
                    <tr>
                        <th class="col-md-2">Servicios</th>
                        <th class="col-md-1">Estatus</th>
                        <th class="col-md-2 text-center">Recoleccion</th>
                        <th class="col-md-2 text-center">Entrega</th>
                        <th class="col-md-2">Cantidad Recolección</th>
                        <th class="col-md-2">Cantidad Entrega</th>
                        <th class="col-md-1">Total</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <td>ENTREGAS A  GAS</td>
                    <td class="text-success">INICIADA</td>
                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                    <td class="text-center"></td>
                    <td>$100</td>
                    <td>$1100</td>
                    <td>$1200</td>
                    </tr>
                    <tr>
                        <td>ENTREGAS A DOMICILIO</td>
                        <td class="text-danger">EN PROCESO</td>
                        <td class="text-center"><i class="fas fa-check text-success"></i></td>
                        <td class="text-center"><i class="fas fa-check text-success"></i></td>
                        <td>$1000</td>
                        <td>$1100</td>
                        <td>$2200</td>
                        </tr>
                        <tr>
                            <td>ENTREGAS A DOMICILIO</td>
                            <td class="text-info">FINALIZADA</td>
                            <td class="text-center"></i></td>
                            <td class="text-center"><i class="fas fa-check text-success"></i></td>
                            <td>$1000</td>
                            <td>$1100</td>
                            <td>$2200</td>
                            </tr>
                </tbody>
            </table>
   </div> 
</div>  
</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

