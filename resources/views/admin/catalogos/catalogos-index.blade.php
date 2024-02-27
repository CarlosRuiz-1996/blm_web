@extends('adminlte::page')


@section('title', 'Catalogos')

@section('content_header')
    <h1>Gestion de Catalogos</h1>


  
@stop

@section('content')

  <!-- Content Row -->
  <div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2 text-center">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Personal de seguridad</div>
                        <br>
                        <a class="btn btn-primary text-center" href="viewEquipos.php">Editar</a>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2 text-center">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Estado rutas</div>
                        <br>
                        <a class="btn btn-primary text-center" href="viewEstadoRutas.php">Editar</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2 text-center">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Puestos</div>
                        <br>
                        <a class="btn btn-primary text-center" href="viewPuestoEquipo.php">Editar</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<!-- Content Row -->
<div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2 text-center">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Precio Cliente </div>
                        <br>
                        <a class="btn btn-primary text-center" href="viewPrecios.php">Editar</a>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2 text-center">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Perfiles</div>
                        <br>
                        <a class="btn btn-primary text-center" href="viewPerfiles.php">Editar</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2 text-center">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Áreas</div>
                        <br>
                        <a class="btn btn-primary text-center" href="viewAreas.php">Editar</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>



<!-- Content Row -->
<div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2 text-center">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Vigencia </div>
                        <br>
                        <a class="btn btn-primary text-center" href="viewVigencia.php">Editar</a>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2 text-center">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Riesgos</div>
                        <br>
                        <a class="btn btn-primary text-center" href="viewRiesgo.php">Editar</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2 text-center">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Tipo Solicitud</div>
                        <br>
                        <a class="btn btn-primary text-center" href="viewTSolicitud.php">Editar</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2 text-center">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Tipo de Pago </div>
                        <br>
                        <a class="btn btn-primary text-center" href="viewTPago.php">Editar</a>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2 text-center">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Consignatario</div>
                        <br>
                        <a class="btn btn-primary text-center" href="viewConsignatorio.php">Editar</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2 text-center">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Ejecutivos</div>
                        <br>
                        <a class="btn btn-primary text-center" href="viewEjecutivo.php">Editar</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2 text-center">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Revisor de Área </div>
                        <br>
                        <a class="btn btn-primary text-center" href="viewRArea.php">Editar</a>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2 text-center">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Retención</div>
                        <br>
                        <a class="btn btn-primary text-center" href="viewRetension.php">Editar</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2 text-center">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Servicios</div>
                        <br>
                        <a class="btn btn-primary text-center" href="viewServicio.php">Editar</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-4 col-md-6 mb-4 pl-0">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2 text-center">
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        Motivo Reprogramación </div>
                    <br>
                    <a class="btn btn-primary text-center" href="viewMotivoReprogramacion.php">Editar</a>
                </div>

            </div>
        </div>
    </div>
</div>
@stop
