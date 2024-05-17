<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\cotizacion_servicio;
use App\Models\Ctg_Cp;
use App\Models\Ctg_Estado;
use App\Models\Ctg_Municipio;
use App\Models\ctg_precio_servicio;
use App\Models\ctg_servicios;
use App\Models\Ctg_Tipo_Cliente;
use App\Models\CtgServicios;
use App\Models\Servicios;
use App\Models\servicios_conceptos_foraneos;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;
use Symfony\Contracts\Service\Attribute\Required;

class CrearTablaCotizacion extends Component
{
    public $data = [];
    public $dataforaneo = [];
    public $dataServicioForaneo = [];
    public $servicioId;
    public $nombreServicio;
    public $tipoServicio;
    public $unidadMedida;
    public $precioUnitario;
    public $editarPrecio;
    public $cantidad = 1;
    public $isAdmin;
    public $total;
    public $estados;
    public $municipios;
    public $cp;
    public $colonias;
    public $cp_invalido;
    public $ctg_cp_id = "";
    public $puesto = "";
    public $nombreContacto = "";
    public $razonSocial;
    public $rfc;
    public $ctg_tipo_cliente_id;
    public $tipoClientelist;
    public $calleNumero;
    public $telefono;
    public $correoElectronico;
    public $vigencia;
    public $condicionpago;
    public $condicionpagolist;
    public $servicios;
    public $precio_servicio;
    public $totalreal;
    public $valoridcoti;
    public $valoriidser;
    public $valoridcliente;
    public $valoridusuario;
    public $password;
    public $apepaterno;
    public $apematerno;
    public $editarPreciocheck;
    public $cantidadcheck;
    public $cantidadhabilitado = false;
    public $editarPreciohabilitado = false;
    public $foraneos = false;
    public $checkforaneo;
    public $inicioruta;
    public $destinoruta;
    public $km;
    public $costokm;
    public $totalkmprecio;
    public $miles;
    public $milesprecio;
    public $costomiles;
    public $goperacion;
    public $iva;
    public $totaliva;
    public $sumatotal;

    public $consepforaneo;
    public $listaForaneos = [];
    public $listaForaneosguarda = [];
    public $editIndex = null; // Índice de la fila que se está editando


    public $folioctg;
    public $tipoctg;
    public $descripcionctg;
    public $unidadctg;
    public $bloqser;
    public $precioconsepforaneo;
    public $costototalservicios;
    public $cantidadlleva;
    public $subtotalforaneo;
    public $cantidadfora;
    public $editar = true;
    public $valoreditar = 0;


    public function mount()
    {
        $this->tipoClientelist = Ctg_Tipo_Cliente::all();
        $this->condicionpagolist = collect([
            (object)['id' => 1, 'nombre' => 'Efectivo'],
            (object)['id' => 2, 'nombre' => 'Transferencia Electrónica'],
        ]);
        $this->colonias = collect();
        $this->servicios = ctg_servicios::all();
        $this->precio_servicio = ctg_precio_servicio::all();
        $this->totalreal = 0;
        $this->checkforaneo = false;
        $this->cantidadhabilitado = false;
        $this->editarPreciohabilitado = false;
        $this->bloqser = false;
        $this->km = 0.0;
        $this->costokm = 0.0;
        $this->totalkmprecio = 0.0;
        $this->miles = 0.0;
        $this->milesprecio = 0.0;
        $this->costomiles = 0.0;
        $this->goperacion = 0.0;
        $this->iva = 16;
        $this->totaliva = 0.0;
        $this->sumatotal = 0.0;
        $this->costototalservicios = 0.0;
        $this->cantidadlleva = 0.0;
        $this->subtotalforaneo = 0.0;
    }

    public function render()
    {
        return view('livewire.crear-tabla-cotizacion');
    }

    public function editarDeListaForaneaConcepto($index)
    {
        $this->editIndex = $index;
    }
    
    public function guardarEdicion($index)
    {
       
        $this->editIndex = null;
        
        // Calcula el costo total de servicios
        $costoTotalServicios = 0;
        foreach ($this->listaForaneos as $key => $item) {
            if ($key === $index) {
                // Actualiza los datos de la fila editada
                $this->listaForaneosguarda[$key]['consepforaneo'] = $item['consepforaneo'];
                $this->listaForaneosguarda[$key]['precioconsepforaneo'] = $item['precioconsepforaneo'];
                $this->listaForaneosguarda[$key]['cantidadfora'] = $item['cantidadfora'];
            }
            // Agregar el precio de cada servicio al costo total
            $costoTotalServicios += $item['precioconsepforaneo'] * $item['cantidadfora'];
        }
    
        // Asignar el costo total de servicios a la propiedad correspondiente
        $this->costototalservicios = $costoTotalServicios;
        $this->propertyUpdated('');
    }

    public function validarCp()
    {
        $this->validate([
            'cp' => 'required|digits_between:1,5',
        ], [
            'cp.digits_between' => 'El código postal solo contiene 5 digitos.',
            'cp.required' => 'Código postal requerido.',

        ]);
        $codigo = DB::select("
                SELECT DISTINCT cp.id, cp.colonia, m.municipio, e.name 
                FROM ctg_cp cp 
                LEFT JOIN ctg_estados e ON e.id = cp.ctg_estado_id
                LEFT JOIN ctg_municipios m ON m.id = cp.ctg_municipio_id AND m.ctg_estado_id = e.id 
                WHERE cp LIKE CONCAT('%', " . $this->cp . " , '%')
            ");
        if ($codigo) {
            $this->municipios = $codigo[0]->municipio;
            $this->estados = $codigo[0]->name;
            $this->colonias = $codigo;
            $this->cp_invalido = "";
        } else {
            $this->cp_invalido = "Codigo postal no valido";
        }
    }

    public function llenartabla()
    {
        $valorcheckforaneo = $this->checkforaneo ? true : false;
        if (!$valorcheckforaneo) {
            $this->validate([
                'servicioId' => 'required',
                'nombreServicio' => 'required',
                'tipoServicio' => 'required',
                'unidadMedida' => 'required',
                'total' => 'required|numeric',
            ]);

            // Verificar si el check de editar precio está seleccionado
            if ($this->editarPreciohabilitado) {
                $this->validate([
                    'editarPrecio' => 'required|numeric',
                ]);
                $this->precioUnitario = $this->editarPrecio;
            } else {
                $this->validate([
                    'precioUnitario' => 'required|numeric',
                ]);
            }

            // Verificar si el check de editar cantidad está seleccionado
            if ($this->cantidadhabilitado) {
                $this->validate([
                    'cantidad' => 'required|numeric',
                ]);
            } else {
                $this->validate([
                    'cantidad' => 'required|numeric',
                ]);
            }
            $this->totalreal = floatval($this->total) + floatval($this->totalreal);
            $especial = $this->isAdmin ? 'Especial' : 'Normal';
            $this->data[] = [
                'id' => count($this->data) + 1,
                'servicioId' => $this->servicioId,
                'nombreservicio' => $this->nombreServicio,
                'cantidad' => $this->cantidad,
                'tiposervicio' => $this->tipoServicio,
                'unidadmedida' => $this->unidadMedida,
                'preciounitario' => $this->precioUnitario,
                'editarPrecio' => $this->editarPrecio,
                'isAdmin' => $especial,
                'total' => $this->total,
            ];
        } else {
            $this->validate([
                'inicioruta' => 'required',
                'destinoruta' => 'required',
                'km' => 'required',
                'costokm' => 'required',
                'totalkmprecio' => 'required',
                'miles' => 'required',
                'milesprecio' => 'required',
                'costomiles' => 'required',
                'goperacion' => 'required',
                'iva' => 'required',
                'totaliva' => 'required',
                'sumatotal' => 'required',
                'cantidadlleva' => 'required',
            ]);
            if (count($this->listaForaneosguarda) > 0) {
                $this->dataforaneo[] = [
                    'id' => count($this->dataforaneo) + 1,
                    'inicioruta' => $this->inicioruta,
                    'destinoruta' => $this->destinoruta,
                    'km' => $this->km,
                    'costokm' => $this->costokm,
                    'totalkmprecio' => $this->totalkmprecio,
                    'miles' => $this->miles,
                    'milesprecio' => $this->milesprecio,
                    'costomiles' => $this->costomiles,
                    'goperacion' => $this->goperacion,
                    'iva' => $this->iva,
                    'totaliva' => $this->totaliva,
                    'sumatotal' => $this->sumatotal,
                    'cantidadlleva' => $this->cantidadlleva,
                ];
                $this->totalreal = $this->sumatotal;
                $this->bloqser = true;
                $this->limpiarCampos();
            } else {
                $this->dispatch('errorTabla', ['La cotización debe contener Servicios']);
            }
        }

        // Limpiar los campos después de agregar un nuevo elemento


        return view('livewire.crear-tabla-cotizacion');
    }

    private function limpiarCampos()
    {
        // Restablecer los valores de los campos a su estado inicial o valores predeterminados
        $this->servicioId = '';
        $this->nombreServicio = '';
        $this->tipoServicio = '';
        $this->unidadMedida = '';
        $this->precioUnitario = '';
        $this->editarPrecio = '';
        $this->cantidad = 1;
        $this->isAdmin = '';
        $this->total = '';

        $this->inicioruta = '';
        $this->destinoruta = '';
        $this->km = 0.0;
        $this->costokm = 0.0;
        $this->totalkmprecio = 0.0;
        $this->miles = 0.0;
        $this->milesprecio = 0.0;
        $this->costomiles = 0.0;
        $this->goperacion = 0.0;
        $this->iva = 0.0;
        $this->totaliva = 0.0;
        $this->sumatotal = 0.0;
        $this->listaForaneos = [];
        $this->cantidadlleva = 0.0;
        $this->cantidadfora = 1;
    }
    public $cot_id = 0;
    #[On('save-cotizacion')]
    public function validaInfo()
    {
        $this->validate([
            'cp' => 'required|digits_between:1,5',
            'razonSocial' => 'required|string',
            'rfc' => 'required|string|unique:clientes,rfc_cliente',
            'ctg_tipo_cliente_id' => 'required|in:1,2,3',
            'nombreContacto' => 'required|string',
            'apematerno' => 'required|string',
            'apepaterno' => 'required|string',
            'puesto' => 'required|string',
            'estados' => 'required|string',
            'municipios' => 'required|string',
            'ctg_cp_id' => 'required|numeric',
            'calleNumero' => 'required|string',
            'telefono' => 'required|string|regex:/^[0-9]{8,10}$/',
            'correoElectronico' => 'required|email|unique:users,email',
            'vigencia' => 'required',
            'condicionpago' => 'required',


        ], [
            'razonSocial.required' => 'La Razón Social es requerida.',
            'rfc.required' => 'El RFC es requerido.',
            'ctg_tipo_cliente_id.required' => 'El Tipo de Cliente es requerido.',
            'nombreContacto.required' => 'El Nombre del Contacto es requerido.',
            'puesto.required' => 'El Puesto es requerido.',
            'cp.digits_between' => 'El código postal debe tener entre 1 y 5 dígitos.',
            'cp.required' => 'Código postal requerido.',
            'municipios.required' => 'La Alcaldía/Municipio es requerida.',
            'ctg_cp_id.required' => 'La colonia es requerida.',
            'calleNumero.required' => 'La Calle y Número es requerida.',
            'telefono.required' => 'El Teléfono es requerido.',
            'correoElectronico.required' => 'El Correo Electrónico es requerido.',
            'vigencia.required' => 'La vigencia es requerido.',
            'condicionpago.required' => 'Condicion de pago es requerido.',
            'estados.required' => 'El estado es requerido.',


        ]);

        // Verificar si $this->data no está vacío
        if (!empty($this->data) || !empty($this->dataforaneo)) {
            try {
                DB::transaction(function () {
                    // Ingreso en la tabla usuarios
                    $user = User::create([
                        'name' => $this->nombreContacto,
                        'paterno' => $this->apepaterno,
                        'materno' => $this->apematerno,
                        'email' => $this->correoElectronico,
                        'password' => bcrypt(strtolower($this->rfc))
                    ]);
                    $user->roles()->sync(4); // Asigno rol 4 que será de cliente.

                    $this->valoridusuario = $user->id; // usuario_id para relacionar con la tabla cliente
                    // Creo cliente
                    $this->valoridcliente = Cliente::create([
                        'user_id' => $this->valoridusuario,
                        'puesto' => $this->puesto,
                        'direccion' => $this->calleNumero,
                        'ctg_cp_id' => $this->ctg_cp_id,
                        'razon_social' => $this->razonSocial,
                        'rfc_cliente' => $this->rfc,
                        'phone' => $this->telefono,
                        'ctg_tipo_cliente_id' => $this->ctg_tipo_cliente_id,
                        'status_cliente' => 0
                    ]);

                    $this->valoridcoti = Cotizacion::create([
                        'total' => $this->totalreal,
                        'vigencia' => $this->vigencia,
                        'ctg_tipo_pago_id' => $this->condicionpago,
                        'cliente_id' => $this->valoridcliente->id,
                        'status_cotizacion' => 1,
                    ]);

                    // Obtener el ID de la cotización recién creada
                    $cotizacionIdreturn = $this->valoridcoti->id;
                    $this->cot_id = $this->valoridcoti->id;

                    // Realizar las inserciones en la base de datos
                    if (empty($this->dataforaneo)) {
                        foreach ($this->data as $datos) {
                            // Realizar la inserción en la base de datos
                            $this->valoriidser = Servicios::create([
                                'precio_unitario' => $datos['preciounitario'],
                                'cantidad' => $datos['cantidad'],
                                'subtotal' => $datos['total'],
                                'ctg_servicios_id' => $datos['servicioId'],
                                'servicio_especial' => $datos['isAdmin'] ? 1 : 0,
                                'status_servicio' => 1,
                                'cliente_id' => $this->valoridcliente->id,
                            ]);

                            // Obtener el ID del servicio recién creado
                            $servicioIdreturn = $this->valoriidser->id;
                            cotizacion_servicio::create([
                                'cotizacion_id' => $cotizacionIdreturn,
                                'servicio_id' => $servicioIdreturn,
                                'status_cotizacion_servicio' => '1'
                            ]);
                        }
                    } else {
                        foreach ($this->dataforaneo as $datosf) {
                            // Realizar la inserción en la base de datos
                            $this->valoriidser = Servicios::create([
                                'precio_unitario' => $datosf['sumatotal'],
                                'cantidad' => 1,
                                'subtotal' => $datosf['sumatotal'],
                                'servicio_especial' => 1,
                                'status_servicio' => 1,
                                'kilometros' => $datosf['km'],
                                'kilometros_costo' => $datosf['costokm'],
                                'miles' => $datosf['miles'],
                                'miles_costo' => $datosf['milesprecio'],
                                'servicio_foraneo' => 1,
                                'gastos_operaciones' => $datosf['goperacion'],
                                'iva' => $datosf['totaliva'],
                                'cliente_id' => $this->valoridcliente->id,
                                'foraneo_destino' => $datosf['destinoruta'],
                                'foraneo_inicio'  => $datosf['inicioruta'],
                                'montotransportar_foraneo'  => $datosf['cantidadlleva'],

                            ]);

                            // Obtener el ID del servicio recién creado
                            $servicioIdreturn = $this->valoriidser->id;
                            cotizacion_servicio::create([
                                'cotizacion_id' => $cotizacionIdreturn,
                                'servicio_id' => $servicioIdreturn,
                                'status_cotizacion_servicio' => '1'
                            ]);
                        }
                        foreach ($this->listaForaneosguarda as $concepto) {
                            servicios_conceptos_foraneos::create([
                                'concepto' => $concepto['consepforaneo'], // Aquí ajusta según la estructura de tu array
                                'costo' => $concepto['precioconsepforaneo'], // Aquí ajusta según la estructura de tu array
                                'servicio_id' => $this->valoriidser->id,
                                'cantidadfora' => $concepto['cantidadfora'],
                            ]);
                        }
                    }
                });

                $this->dispatch('success-cotizacion', ['La cotización se creó con éxito', $this->cot_id]);
            } catch (\Exception $e) {
                // Manejar la excepción si ocurre algún error durante la transacción
                $this->dispatch('errorTabla', ['Ocurrió un error al procesar la cotización']);
            }
        } else {
            $this->dispatch('errorTabla', ['La cotización debe contener Servicios']);
        }
    }
    public function updatedServicioId($value)
    {
        $servicios = ctg_servicios::where('id', $value)->get();
        $this->nombreServicio = $servicios[0]->descripcion;
        $this->tipoServicio = $servicios[0]->tipo;
        $this->unidadMedida = $servicios[0]->unidad;
    }
    public function updatedCantidad()
    {
        if ($this->editarPreciohabilitado) {
            $precioUnitarioNumerico = floatval($this->editarPrecio);
        } else {
            $precioUnitarioNumerico = floatval($this->precioUnitario);
        }
        $cantidadNumerica = floatval($this->cantidad);

        $this->total = $precioUnitarioNumerico * $cantidadNumerica;
    }
    public function updatedPrecioUnitario()
    {
        if ($this->editarPreciohabilitado) {
            $precioUnitarioNumerico = floatval($this->editarPrecio);
        } else {
            $precioUnitarioNumerico = floatval($this->precioUnitario);
        }
        $cantidadNumerica = floatval($this->cantidad);

        $this->total = $precioUnitarioNumerico * $cantidadNumerica;
    }
    public function updatededitarPrecio()
    {
        if ($this->editarPreciohabilitado) {
            $precioUnitarioNumerico = floatval($this->editarPrecio);
        } else {
            $precioUnitarioNumerico = floatval($this->precioUnitario);
        }
        $cantidadNumerica = floatval($this->cantidad);

        $this->total = $precioUnitarioNumerico * $cantidadNumerica;
    }
    public function updatedCantidadcheck()
    {
        if ($this->cantidadhabilitado == false) {

            $this->cantidadhabilitado = true;
        } else {
            $this->cantidadhabilitado = false;
            $this->cantidad = 1;
            $this->updatedCantidad();
        }
    }
    public function updatededitarPreciocheck()
    {
        if ($this->editarPreciohabilitado == false) {

            $this->editarPreciohabilitado = true;
        } else {
            $this->editarPreciohabilitado = false;
            $this->editarPrecio = "";
            $this->updatedPrecioUnitario();
        }
    }
    public function updatedCheckForaneo()
    {
        if ($this->checkforaneo) {
            $this->foraneos = true;
        } else {
            $this->foraneos = false;
        }
    }
    public function updated($propertyName)
    {
        $this->propertyUpdated($propertyName);
    }

    public function propertyUpdated($propertyName)
    {
        if ($this->checkforaneo) {
            if ($propertyName === 'km' || $propertyName === 'costokm') {
                $this->totalkmprecio = (float)$this->km * (float)$this->costokm;
            }

            if ($propertyName === 'miles' || $propertyName === 'milesprecio') {
                $this->costomiles = (float)$this->miles * (float)$this->milesprecio;
            }

            $this->subtotalforaneo = $this->costomiles + $this->totalkmprecio + (float)$this->goperacion + (float)$this->costototalservicios;

            $this->totaliva = round(((float)$this->iva / 100.0) * ($this->costomiles + $this->totalkmprecio + (float)$this->goperacion + (float)$this->costototalservicios), 2);

            $this->sumatotal = (float)$this->totaliva + (float)$this->costomiles + (float)$this->totalkmprecio + (float)$this->goperacion + (float)$this->costototalservicios;
        }
    }


    public function agregarALista()
    {
        $this->validate([
            'consepforaneo' => 'required',
            'precioconsepforaneo' => 'required',
            'cantidadfora' => 'required',
        ], [
            'consepforaneo.required' => 'El servivio es requerida.',
            'precioconsepforaneo.required' => 'El servivio es requerida.',
            'cantidadfora.required' => 'El servivio es requerida.',
        ]);

        if ($this->consepforaneo && $this->precioconsepforaneo) {
            $this->listaForaneos[] = array(
                'consepforaneo' => $this->consepforaneo,
                'precioconsepforaneo' => $this->precioconsepforaneo,
                'cantidadfora' => $this->cantidadfora
            );
            $this->listaForaneosguarda[] = array(
                'consepforaneo' => $this->consepforaneo,
                'precioconsepforaneo' => $this->precioconsepforaneo,
                'cantidadfora' => $this->cantidadfora
            );
        }
        $costoTotalServicios = 0;

        // Iterar sobre $this->listaForaneos para sumar los precios
        foreach ($this->listaForaneos as $item) {
            // Agregar el precio de cada servicio al costo total
            $costoTotalServicios += $item['precioconsepforaneo'] * $item['cantidadfora'];
        }

        // Asignar el costo total de servicios a la propiedad correspondiente
        $this->costototalservicios = $costoTotalServicios;
        $this->propertyUpdated('');
        // Limpiar el campo después de agregarlo a la lista
        $this->consepforaneo = '';
        $this->cantidadfora = 1;
        $this->precioconsepforaneo = '';
    }
    public function eliminarDeLista($index)
    {
        $costoTotalServicios = 0.0;
        unset($this->listaForaneos[$index]);
        unset($this->listaForaneosguarda[$index]);
        foreach ($this->listaForaneos as $item) {
            // Agregar el precio de cada servicio al costo total
            $costoTotalServicios += $item['precioconsepforaneo'] * $item['cantidadfora'];
        }

        // Asignar el costo total de servicios a la propiedad correspondiente
        $this->costototalservicios = $costoTotalServicios;
        $this->propertyUpdated('');
    }


    public function crearServicioctg()
    {
        $this->validate([
            'folioctg' => 'required|unique:ctg_servicios,folio',
            'tipoctg' => 'required',
            'descripcionctg' => 'required',
            'unidadctg' => 'required',
        ], [
            'folioctg.required' => 'El folio es requerido.',
            'folioctg.unique' => 'El folio ya existe en la tabla de servicios.',
            'tipoctg.required' => 'El tipo es requerido.',
            'descripcionctg.required' => 'La descripción es requerida.',
            'unidadctg.required' => 'La unidad es requerida.',
        ]);
        CtgServicios::create([
            'folio' => $this->folioctg,
            'tipo' => $this->tipoctg,
            'descripcion' => $this->descripcionctg,
            'unidad' => $this->unidadctg,
            'status_servicio' => 1,
        ]);
        $this->folioctg = '';
        $this->tipoctg = '';
        $this->descripcionctg = '';
        $this->unidadctg = '';
        $this->servicios = ctg_servicios::all();
        // Despachar el evento
        $this->dispatch('successservicio', ['El servicio se creó con éxito']);
    }

    public function eliminarDeListaNormal($id)
    {
        $totalEliminado = 0;

        foreach ($this->data as $key => $item) {
            if ($item['id'] == $id) {
                $totalEliminado = floatval($item['total']);
                unset($this->data[$key]);
                break; // Terminamos el bucle una vez que encontramos y eliminamos el elemento
            }
        }

        // Recalcular el total
        $this->totalreal -= $totalEliminado;
    }
    public function eliminarDeListaForanea($id)
    {
        $this->dataforaneo = [];
        $this->listaForaneos = [];
        $this->listaForaneosguarda = [];
        // Recalcular el total
        $this->totalreal = 0;
    }
    public function editarDeListaNormal($id)
    {
        $this->editar = false;
        $this->valoreditar = $id;
        foreach ($this->data as $key => $item) {
            if ($item['id'] == $id) {
                $this->servicioId = $item['servicioId'];
                $this->nombreServicio = $item['nombreservicio'];
                $this->cantidad = $item['cantidad'];
                $this->tipoServicio = $item['tiposervicio'];
                $this->unidadMedida = $item['unidadmedida'];
                $this->precioUnitario = $item['preciounitario'];
                $this->editarPrecio = $item['editarPrecio'];
                $this->total = $item['total'];
                // Otros valores si es necesario
                break; // Terminamos el bucle una vez que encontramos el elemento
            }
        }
    }

    public function  editarservicioNormal($id)
    {
        $valorcheckforaneo = $this->checkforaneo ? true : false;
        if (!$valorcheckforaneo) {
            $this->validate([
                'servicioId' => 'required',
                'nombreServicio' => 'required',
                'tipoServicio' => 'required',
                'unidadMedida' => 'required',
                'total' => 'required|numeric',
            ]);

            // Verificar si el check de editar precio está seleccionado
            if ($this->editarPreciohabilitado) {
                $this->validate([
                    'editarPrecio' => 'required|numeric',
                ]);
                $this->precioUnitario = $this->editarPrecio;
            } else {
                $this->validate([
                    'precioUnitario' => 'required|numeric',
                ]);
            }

            // Verificar si el check de editar cantidad está seleccionado
            if ($this->cantidadhabilitado) {
                $this->validate([
                    'cantidad' => 'required|numeric',
                ]);
            } else {
                $this->validate([
                    'cantidad' => 'required|numeric',
                ]);
            }
            $this->totalreal = 0;
            foreach ($this->data as $key => $item) {

                if ($item['id'] == $id) {
                    $especial = $this->isAdmin ? 'Especial' : 'Normal';
                    $this->data[$key]['servicioId'] = $this->servicioId;
                    $this->data[$key]['nombreservicio'] = $this->nombreServicio;
                    $this->data[$key]['cantidad'] = $this->cantidad;
                    $this->data[$key]['tiposervicio'] = $this->tipoServicio;
                    $this->data[$key]['unidadmedida'] = $this->unidadMedida;
                    $this->data[$key]['preciounitario'] = $this->precioUnitario;
                    $this->data[$key]['editarPrecio'] = $this->editarPrecio;
                    $this->data[$key]['isAdmin'] = $especial;
                    $this->data[$key]['total'] = $this->total;
                    // Otros valores si es necesario
                    break; // Terminamos el bucle una vez que encontramos el elemento
                }
            }
            foreach ($this->data as $item) {
                // Sumamos el valor de 'total' de cada elemento al total real
                $this->totalreal += floatval($item['total']);
            }
        }else{
            $this->validate([
                'inicioruta' => 'required',
                'destinoruta' => 'required',
                'km' => 'required',
                'costokm' => 'required',
                'totalkmprecio' => 'required',
                'miles' => 'required',
                'milesprecio' => 'required',
                'costomiles' => 'required',
                'goperacion' => 'required',
                'iva' => 'required',
                'totaliva' => 'required',
                'sumatotal' => 'required',
                'cantidadlleva' => 'required',
            ]);
            $this->totalreal = 0;
            foreach ($this->dataforaneo as $key => $item) {

                if ($item['id'] == $id) {
                    $especial = $this->isAdmin ? 'Especial' : 'Normal';
                    $this->dataforaneo[$key]['inicioruta'] = $this->inicioruta;
                    $this->dataforaneo[$key]['destinoruta'] = $this->destinoruta;
                    $this->dataforaneo[$key]['km'] = $this->km;
                    $this->dataforaneo[$key]['costokm'] = $this->costokm;
                    $this->dataforaneo[$key]['totalkmprecio'] = $this->totalkmprecio;
                    $this->dataforaneo[$key]['miles'] = $this->miles;
                    $this->dataforaneo[$key]['milesprecio'] = $this->milesprecio;
                    $this->dataforaneo[$key]['costomiles'] = $this->costomiles;
                    $this->dataforaneo[$key]['goperacion'] = $this->goperacion;
                    $this->dataforaneo[$key]['iva'] = $this->iva;
                    $this->dataforaneo[$key]['totaliva'] = $this->totaliva;
                    $this->dataforaneo[$key]['sumatotal'] = $this->sumatotal;
                    $this->dataforaneo[$key]['cantidadlleva'] = $this->cantidadlleva;
                    // Otros valores si es necesario
                    break; // Terminamos el bucle una vez que encontramos el elemento
                }
            }
            foreach ($this->dataforaneo as $item) {
                // Sumamos el valor de 'total' de cada elemento al total real
                $this->totalreal += floatval($item['sumatotal']);
            }
    }
    }
    public function  editarDeListaForanea($id)
    {
        $this->editar = false;
        $this->valoreditar = $id;
        foreach ($this->dataforaneo as $key => $item) {
            if ($item['id'] == $id) {
            $this->inicioruta = $item['inicioruta'];
            $this->destinoruta = $item['destinoruta'];
            $this->km = $item['km'];
            $this->costokm = $item['costokm'];
            $this->totalkmprecio = $item['totalkmprecio'];
            $this->miles = $item['miles'];
            $this->milesprecio = $item['milesprecio'];
            $this->costomiles = $item['costomiles'];
        
            $this->goperacion = $item['goperacion'];
            $this->iva = $item['iva'];
            $this->totaliva = $item['totaliva'];
            $this->sumatotal = $item['sumatotal'];
            $this->cantidadlleva = $item['cantidadlleva'];
            break;
            }
           
        }
        $this->listaForaneos=$this->listaForaneosguarda;
    }
}