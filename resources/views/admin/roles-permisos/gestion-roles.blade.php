@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
    <h1 class="ml-2">Gestion de Roles</h1>
        {{-- vista de los la vista de roles --}}

@stop
@section('content')
    <x-alert />


    <div class="container-fluid">
        <div class="form-group mt-0 text-right">
            <button type="submit" class="btn btn-info" data-toggle="modal" data-target="#modal-nuevo">Nuevo</button>
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
                            @foreach ($roles as $role)
                                <tr>
                                    <td>
                                        {{ $role->id }}
                                    </td>
                                    <td>
                                        {{ $role->name }}
                                    </td>
                                    <td>

                                        <div class="btn-group">

                                            @role('Super')
                                                <button class="btn btn-xs btn-default mx-1 shadow" title="Editar"
                                                    data-toggle="modal" data-target="#modal-edit"
                                                    data-rol="{{ $role }}">
                                                    <i class="fa fa-lg fa-fw fa-pen" style="color:royalblue"></i>
                                                </button>

                                                <form id="deleteForm{{ $role->id }}"
                                                    action="{{ route('roles.destroy', $role) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-xs btn-default text-danger mx-1 shadow"
                                                        title="Eliminar" onclick="confirmDelete({{ $role->id }})"
                                                        type="button">
                                                        <i class="fa fa-lg fa-fw fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endrole
                                            <button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details"
                                                data-toggle="modal" data-target="#modal-permisos"
                                                data-rol-id="{{ $role->id }}" data-rol-name="{{ $role->name }}">


                                                <i class="fa fa-lg fa-fw fa-eye"></i>
                                            </button>
                                            @role('Super')
                                                <a href="{{ route('roles.edit', $role) }}"
                                                    class="btn btn-xs btn-default text-warning mx-1 shadow" title="Edit">
                                                    <i class="fa fa-lg fa-fw fa-lock"></i>
                                                </a>
                                            @endrole
                                        </div>

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
    <x-adminlte-modal id="modal-nuevo" title="Nuevo rol" theme="primary" icon="fas fa-bolt" size='lg'
        disable-animations>
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="row">
                <x-adminlte-input name="rol" label="Rol" placeholder="Ingresa el nuevo rol" fgroup-class="col-md-6"
                    disable-feedback />


            </div>
            <x-adminlte-button class="btn-flat" type="submit" label="Guardar" theme="primary" icon="fas fa-lg fa-save" />
        </form>
    </x-adminlte-modal>

    {{-- EDITAR --}}
    <x-adminlte-modal id="modal-edit" title="Editar rol" theme="primary" icon="fas fa-bolt" size='lg'
        disable-animations>
        <form action="{{ route('rol.actualizar', '') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <x-adminlte-input name="new_name" label="Rol" placeholder="Ingresa el nuevo rol" fgroup-class="col-md-6"
                    disable-feedback />


            </div>
            <x-adminlte-button class="btn-flat" type="submit" label="Guardar" theme="primary" icon="fas fa-lg fa-save" />
        </form>
    </x-adminlte-modal>


    {{-- permisos --}}
    <x-adminlte-modal id="modal-permisos" title="" theme="primary" icon="fas fa-bolt" size='lg'
        disable-animations>
        <div id="permissions-list"></div>
    </x-adminlte-modal>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#modal-edit').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var rol = button.data('rol');
                document.querySelector('#modal-edit input[name="new_name"]').value = rol['name'];
                document.querySelector('#modal-edit  form').setAttribute('action',
                    '{{ route('rol.actualizar', '') }}/' + rol['id']);


            });


            $('#modal-permisos').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var rolId = button.data('rol-id');
                var rolname = button.data('rol-name');
                // Limpia la lista de permisos
                $('#permissions-list').empty();
                $(this).find('.modal-title').text('Lista de permisos de ' + rolname);

                // Realiza una petición AJAX para obtener los permisos del rol
                fetch('/api/roles/' + rolId)
                    .then(response => response.json())
                    .then(data => {

                        // Por ejemplo, puedes mostrar los permisos en una lista
                        if (data.permissions.length > 0) {
                            // Si hay permisos, los mostramos en la lista
                            data.permissions.forEach(permission => {
                                $('#permissions-list').append('<ul><li>' + permission.name +
                                    '</li></ul>');
                            });
                        } else {
                            // Si no hay permisos, mostramos un mensaje indicando que no hay permisos
                            $('#permissions-list').append('<p>No hay permisos asignados.</p>');
                        }
                    })
                    .catch(error => {
                        // Maneja cualquier error que ocurra durante la solicitud
                        console.error('Error al obtener los detalles del rol:', error);
                    });
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
