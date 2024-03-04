<div>
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="card-body">
                <h5><b>Razón Social: </b>{{ $cliente->razon_social }}.</h5>
                <h5><b>RFC: </b>{{ $cliente->rfc_cliente }}.</h5>

                <h5><b>Tipo de cliente: </b>{{ $cliente->tipo_cliente->name }}.</h5>
                <h5><b>Contacto:
                    </b>{{ $cliente->user->name . ' ' . $cliente->user->paterno . ' ' . $cliente->user->materno
                    }}.
                </h5>
                <h5><b>Telefono: </b>{{ $cliente->phone }}.</h5>
                <h5><b>Correo: </b>{{ $cliente->user->email }}.</h5>
                <h5>
                    <b>Dirección: </b>
                    {{ $cliente->direccion }}
                    {{ $cliente->cp->cp }}
                    {{ $cliente->cp->municipio->municipio }},
                    {{ $cliente->cp->estado->name }}.
                </h5>

            </div>
        </div>
    </div>
</div>
