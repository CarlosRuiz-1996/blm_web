@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
    <h1 class="ml-2">Gestion de permisos</h1>
    {{-- vista de los la vista de permnisos --}}
@stop
@section('content')
    <x-alert />
    <div class="container-fluid">
        <div class="form-group mt-0 text-right">
            <button type="submit" class="btn btn-info" data-toggle="modal" data-target="#modalPurple">Nuevo</button>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        {{-- Setup data for datatables --}}
                        @php
                            $heads = ['ID', 'Nombre', ['label' => 'Actiones', 'no-export' => true, 'width' => 20]];

                            $config = [
                                'language' => ['url' => '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'],
                                'columns' => [
                                ['ID' => true, 'type' => 'num'], // Configuración para que la columna ID se ordene numéricamente
                                null, // Columna Nombre
                                ['ID' => false], // Columna de acciones no ordenable
                            ],
                            ];

                        @endphp

                        {{-- Minimal example / fill data using the component slot --}}
                        <x-adminlte-datatable id="table1" :heads="$heads" :config="$config"
                         hoverable   head-theme="dark"
                        >
                            @foreach ($permisos as $permiso)
                                <tr>
                                    <td>
                                        {{ $permiso->id }}
                                    </td>
                                    <td>
                                        {{ $permiso->name }}
                                    </td>

                                    <td>
                                        @role('Super')
                                            <div class="btn-group">
                                                <button class="btn btn-xs btn-default mx-1 shadow" title="Editar"
                                                    data-toggle="modal" data-target="#modal-edit"
                                                    data-permiso="{{ $permiso }}">
                                                    <i class="fa fa-lg fa-fw fa-pen" style="color:royalblue"></i>
                                                </button>
                                                <form id="deleteForm{{ $permiso->id }}"
                                                    action="{{ route('permisos.destroy', $permiso) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-xs btn-default text-danger mx-1 shadow"
                                                        title="Eliminar" onclick="confirmDelete({{ $permiso->id }})"
                                                        type="button">
                                                        <i class="fa fa-lg fa-fw fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            No se puede hacer ninguna acción...
                                        @endrole
                                    </td>

                                </tr>
                            @endforeach
                        </x-adminlte-datatable>
                    </div>
                </div>
            </div>
        </div>
    </div>




    {{-- { {-- Themed --} } --}}
    <x-adminlte-modal id="modalPurple" title="Nuevo permiso" theme="primary" icon="fas fa-bolt" size='lg'
        disable-animations>
        <form action="{{ route('permisos.store') }}" method="POST">
            @csrf
            <div class="row">
                <x-adminlte-input name="permiso" label="Permiso" placeholder="Ingresa el nuevo permiso"
                    fgroup-class="col-md-6" disable-feedback />


            </div>
            <x-adminlte-button class="btn-flat" type="submit" label="Guardar" theme="primary" icon="fas fa-lg fa-save" />
        </form>
    </x-adminlte-modal>


    {{-- EDITAR --}}
    <x-adminlte-modal id="modal-edit" title="Editar permiso" theme="primary" icon="fas fa-bolt" size='lg'
        disable-animations>
        <form action="{{ route('permiso.actualizar', '') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <x-adminlte-input name="new_name" label="Permiso" placeholder="Ingresa el nuevo permiso"
                    fgroup-class="col-md-6" disable-feedback />


            </div>
            <x-adminlte-button class="btn-flat" type="submit" label="Guardar" theme="primary" icon="fas fa-lg fa-save" />
        </form>
    </x-adminlte-modal>
@stop
@section('js')
    <script>
        $(document).ready(function() {
            $('#modal-edit').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var permiso = button.data('permiso');
                document.querySelector('#modal-edit input[name="new_name"]').value = permiso['name'];
                document.querySelector('#modal-edit  form').setAttribute('action',
                    '{{ route('permiso.actualizar', '') }}/' + permiso['id']);


            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: '¡No podrás revertir esto y puede causar fallas en el sistema!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si se confirma, redirige a la ruta de eliminar con el ID del rol
                    document.getElementById('deleteForm' + id).submit();

                }
            });
        }
    </script>
@stop
