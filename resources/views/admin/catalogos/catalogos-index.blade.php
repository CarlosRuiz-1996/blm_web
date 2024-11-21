@extends('adminlte::page')


@section('title', 'Catalogos')

@section('content_header')
    <h1>Gestion de Catalogos</h1>


{{-- listado de cards de catalogos de blm...
aqui se listan los catalogos mas importantes de blm
--}}
@stop

@section('content')

    <!-- Content Row -->
    <div class="row">

       
        <!-- vihiculos -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2 text-center">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Vehiculos</div>
                            <br>
                            <a class="btn btn-primary text-center" href="{{route('catalogo.listar',1)}}">Editar</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
         <!-- marcas vihiculos -->
         <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2 text-center">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                               Marcas de Vehiculos</div>
                            <br>
                            <a class="btn btn-primary text-center" href="{{route('catalogo.listar',2)}}">Editar</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
         <!--modelos vihiculos -->
         <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2 text-center">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Modelos de Vehiculos</div>
                            <br>
                            <a class="btn btn-primary text-center" href="{{route('catalogo.listar',3)}}">Editar</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- ctg rutas -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2 text-center">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Estado rutas</div>
                            <br>
                            <a class="btn btn-primary text-center" href="{{route('catalogo.listar',4)}}">Editar</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2 text-center">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Nombre de Rutas</div>
                            <br>
                            <a class="btn btn-primary text-center" href="{{route('catalogo.listar',5)}}">Editar</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2 text-center">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Riesgos de Rutas</div>
                            <br>
                            <a class="btn btn-primary text-center" href="{{route('catalogo.listar',6)}}">Editar</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2 text-center">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Dias de Rutas</div>
                            <br>
                            <a class="btn btn-primary text-center" href="{{route('catalogo.listar',7)}}">Editar</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2 text-center">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Contratos</div>
                            <br>
                            <a class="btn btn-primary text-center" href="{{route('catalogo.listar',8)}}">Editar</a>
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
                                Areas</div>
                            <br>
                            <a class="btn btn-primary text-center" href="{{route('catalogo.listar',9)}}">Editar</a>
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
                                Horarios de servicio </div>
                            <br>
                            <a class="btn btn-primary text-center" href="{{route('catalogo.listar',13)}}">Editar</a>
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
                                Dias de servicio</div>
                            <br>
                            <a class="btn btn-primary text-center" href="{{route('catalogo.listar',12)}}">Editar</a>
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
                                Dias de entregas</div>
                            <br>
                            <a class="btn btn-primary text-center" href="{{route('catalogo.listar',11)}}">Editar</a>
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
                                Horarios de entrega </div>
                            <br>
                            <a class="btn btn-primary text-center" href="{{route('catalogo.listar',14)}}">Editar</a>
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
                                Tipos de cliente </div>
                            <br>
                            <a class="btn btn-primary text-center" href="{{route('catalogo.listar',15)}}">Editar</a>
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
                            <a class="btn btn-primary text-center" href="{{route('catalogo.listar',16)}}">Editar</a>
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
                            <a class="btn btn-primary text-center" href="{{route('catalogo.listar',17)}}">Editar</a>
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
                            <a class="btn btn-primary text-center" href="{{route('catalogo.listar',10)}}">Editar</a>
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
                                Tipos de servicio</div>
                            <br>
                            <a class="btn btn-primary text-center" href="{{route('catalogo.listar',18)}}">Editar</a>
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
                               Tipos de monedas </div>
                            <br>
                        <a class="btn btn-primary text-center" href="{{route('catalogo.listar',19)}}">Editar</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <!-- Earnings (Monthly) Card Example -->
        {{-- <div class="col-xl-4 col-md-6 mb-4">
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
        </div> --}}

        <!-- Pending Requests Card Example -->
        {{-- <div class="col-xl-4 col-md-6 mb-4">
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
        </div> --}}
    </div>

   
    
@stop
