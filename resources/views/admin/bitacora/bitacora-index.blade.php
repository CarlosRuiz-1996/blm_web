@extends('adminlte::page')


@section('title', 'Bitacora')

@section('content_header')
    <h1 class="ml-2">Bitacora</h1>
@stop
@section('content')
    {{-- @livewire('roles-crear') --}}

    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        {{-- Setup data for datatables --}}
                        @php
                            $heads = ['ID', 'Accion', 'Usuario', 'Direccion ip', ['label' => 'Acciones', 'no-export' => true, 'width' => 20]];

                            $config = [
                                'language' => ['url' => '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'],
                            ];
                        @endphp

                        {{-- Minimal example / fill data using the component slot --}}
                        <x-adminlte-datatable id="table1" :heads="$heads" :config="$config">
                            @foreach ($bitacoras as $bi)
                                <tr>
                                    <td>
                                        {{ $bi->id }}
                                    </td>
                                    <td>
                                        {{ $bi->ctg_accion->name }}
                                    </td>
                                    <td>
                                        {{ $bi->user->name . ' ' . $bi->user->paterno . ' ' . $bi->user->materno }}
                                    </td>
                                    <td>
                                        {{ $bi->user_ip }}
                                    </td>
                                    <td>
                                        @if ($bi->accion == 1 || $bi->accion == 2)
                                            <button class="btn btn-xs btn-default mx-1 shadow" title="Nuevo"
                                                data-toggle="modal" data-target="#detalle" data-detail="{{ $bi->new }}"
                                                data-opcion="1">
                                                <i class="fa fa-lg fa-fw fa-eye" style="color:royalblue"></i>
                                            </button>
                                        @endif
                                        @if ($bi->accion == 2 || $bi->accion == 3)
                                            <button class="btn btn-xs btn-default l mx-1 shadow" title="Viejo"
                                                data-toggle="modal" data-target="#detalle" data-detail="{{ $bi->old }}"
                                                data-opcion="2">
                                                <i class="fa fa-lg fa-fw fa-eye"></i>
                                            </button>
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




    <!-- Modal -->
    <x-adminlte-modal id="detalle" title="" theme="primary" icon="fas fa-bolt" size='lg' disable-animations>
        <div class="modal-body">
            <!-- Contenido del permiso -->
            <div id="modal-new-content"></div>
        </div>
    </x-adminlte-modal>
@stop
@section('js')
    <script>
        $(document).ready(function() {
            $('#detalle').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var newContent = button.data('detail');
                var newContentJson = JSON.stringify(newContent); // Convertir a JSON
                var opcion = button.data('opcion');
                // Mostrar el contenido convertido a JSON dentro del modal
                var title = "";
                if (opcion === 1) {
                    title = 'Detalle del los datos nuevos';
                } else {
                    title = 'Detalle del los datos viejos';
                }
                $(this).find('.modal-title').text(title);
                $('#modal-new-content').html('<pre>' + newContentJson + '</pre>');
            });
        });
    </script>
@stop
