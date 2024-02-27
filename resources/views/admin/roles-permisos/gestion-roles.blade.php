@extends('adminlte::page')


@section('title', 'Dashboard')

@section('content_header')
    <h1 class="ml-2">Gestion de Roles</h1>
@stop
@section('content')
    {{-- @livewire('roles-crear') --}}

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
                            $heads = ['ID', 'Name', ['label' => 'Actions', 'no-export' => true, 'width' => 20]];

                            $config = [
                                'language' => ['url' => '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'],
                            ];
                        @endphp

                        {{-- Minimal example / fill data using the component slot --}}
                        <x-adminlte-datatable id="table1" :heads="$heads" :config="$config">
                            @foreach ($roles as $role)
                                <tr>
                                    <td>
                                        {{ $role->id }}
                                    </td>
                                    <td>
                                        {{ $role->name }}
                                    </td>
                                    <td>
                                        <a href="{{route('roles.edit',$role)}}" class="btn btn-xs btn-default text-primary mx-1 shadow"
                                         title="Edit">
                                            <i class="fa fa-lg fa-fw fa-pen"></i>
                                    </a>
                                        <a href="{{route('roles.destroy',$role)}}" class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                                            <i class="fa fa-lg fa-fw fa-trash"></i>
                                        </a>
                                        <button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                                            <i class="fa fa-lg fa-fw fa-eye"></i>
                                        </button>
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
    <x-adminlte-modal id="modalPurple" title="Nuevo rol" theme="primary" icon="fas fa-bolt" size='lg'
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
@stop
