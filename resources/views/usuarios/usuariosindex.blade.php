@extends('adminlte::page')


@section('title', 'Usuarios')

@section('content_header')
    <h1 class="ml-2">Listado de usuarios</h1>
@stop
@section('content')
    <x-alert />
    <div class="container-fluid">
        <div class="form-group mt-0 text-right">
            {{-- <a href="{{ route('user.create') }}" class="btn btn-info">Nuevo</a> --}}
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">


                    <div class="card-body">
                        {{-- Setup data for datatables --}}
                        @php
                            $heads = [
                                'ID',
                                'Nombre',
                                'Cuenta',
                                'Area',
                                'Fecha de alta',
                                'Estatus',
                                ['label' => 'Actions', 'no-export' => true, 'width' => 20],
                            ];

                            $config = [
                                'language' => ['url' => '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'],
                                'order' => [[0, 'desc']],
                            ];
                        @endphp

                        {{-- Minimal example / fill data using the component slot --}}
                        <x-adminlte-datatable id="table1" :heads="$heads" :config="$config">
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        {{ $user->id }}
                                    </td>
                                    <td>
                                        {{ $user->name . ' ' . $user->paterno . ' ' . $user->materno }}
                                    </td>
                                    <td>

                                        @if ($user->roles->isEmpty())
                                            <div>Sin rol asignado</div>
                                        @else
                                            @foreach ($user->roles as $role)
                                                <div>-{{ $role->name }}</div>
                                            @endforeach
                                        @endif

                                    </td>
                                    <td>
                                        @if ($user->empleado)
                                            {{ $user->empleado->area->name }}
                                        @else
                                            Sin área asignada
                                        @endif
                                    </td>
                                    <td>
                                        {{ $user->created_at }}
                                    </td>
                                    <td>

                                        <i class="fa fa-circle"
                                            style="color:{{ $user->status_user == 1 ? 'green' : 'red' }} "
                                            aria-hidden="true"></i>

                                    </td>
                                    <td>

                                        @if ($user->status_user != 0)
                                            <a href="{{ route('user.edit', $user) }}"
                                                class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                                                <i class="fa fa-lg fa-fw fa-pen"></i>
                                            </a>
                                            <a href="{{ route('user.delete', $user) }}"
                                                class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                            </a>
                                            <a href="{{ route('user.password', $user) }}"
                                                class="btn btn-xs btn-default text-warning mx-1 shadow"
                                                title="Cambiar contraseña">
                                                <i class="fa fa-lg fa-fw fa-key"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('user.reactivar', $user) }}"
                                                class="btn btn-xs btn-default text-primary mx-1 shadow" title="Reactivar">
                                                <i class="fa fa-lg fa-fw fa-arrow-up"></i>
                                            </a>
                                        @endif


                                    </td>
                                </tr>
                            @endforeach
                        </x-adminlte-datatable>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
