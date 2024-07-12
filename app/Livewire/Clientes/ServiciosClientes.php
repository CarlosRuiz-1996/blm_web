<?php

namespace App\Livewire\Clientes;

use App\Livewire\Forms\AnexoForm;
use Livewire\Component;
use App\Livewire\Forms\ClienteActivoForm;
use App\Models\Cliente;
use App\Models\ctg_precio_servicio;
use App\Models\ctg_servicios;
use App\Models\CtgServicios;
use App\Models\Servicios;
use App\Models\servicios_conceptos_foraneos;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use PhpParser\Node\Expr\FuncCall;

class ServiciosClientes extends Component
{
    public ClienteActivoForm $form;
    use WithPagination;
    public $readyToLoad = false;

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
    public $tipoClientelist;
    public $servicios;
    public $precio_servicio;
    public $totalreal;
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

    public $total;
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
    public AnexoForm $form_anexo;

    public function mount(Cliente $cliente)
    {
        $this->form->cliente = $cliente;
        $this->precio_servicio = ctg_precio_servicio::all();
        $this->servicios = ctg_servicios::all();
        // session()->forget('servicio-sucursal');
        // session()->forget('servicio-memo');
        $this->form_anexo->cliente_id = $cliente->id;
        $this->form_anexo->direcconFiscal();
    }
    public function render()
    {
        if ($this->readyToLoad) {
            $servicios_cliente = $this->form->getServicios();
        } else {
            $servicios_cliente = [];
        }
        return view('livewire.clientes.servicios-clientes', compact('servicios_cliente'));
    }

    public function loadServicios()
    {
        $this->readyToLoad = true;
    }

    public function updateServicio(Servicios $servicio, $accion)
    {
        $this->form->updateServicio($servicio, $accion);
        $this->render();
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
        $this->dispatch('success-servicio', ['El servicio se creó con éxito']);
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

        $this->dispatch('sucursal-servico-clienteActivo');
        // return view('livewire.crear-tabla-cotizacion');
    }

    #[On('cliente-servicio-fin')]
    public function finalizar()
    {

        // session()->forget('servicio-sucursal');



        $res = $this->form->saveComplementarios($this->dataforaneo, $this->data, $this->listaForaneosguarda);

        if ($res == 1) {
            $this->dispatch('success-terminado');
            session()->forget('servicio-sucursal');
            session()->forget('servicio-memo');
            $this->clean();
            $this->render();
        } else {
            $this->dispatch('error-terminado');
        }
    }


    #[On('clean-servicio')]
    public function clean()
    {
        $this->reset(
            'foraneos',
            'servicioId',
            'nombreServicio',
            'tipoServicio',
            'unidadMedida',
            'precioUnitario',
            'editarPreciocheck',
            'editarPrecio',
            'cantidadcheck',
            'cantidad',
            'isAdmin',
            'total',
            'checkforaneo',
            'consepforaneo',
            'cantidadfora',
            'precioconsepforaneo',
            'listaForaneos',
            'costototalservicios',
            'cantidadlleva',
            'inicioruta',
            'destinoruta',
            'km',
            'costokm',
            'totalkmprecio',
            'miles',
            'milesprecio',
            'costomiles',
            'goperacion',
            'iva',
            'totaliva',
            'subtotalforaneo',
            'sumatotal',
            'folioctg',
            'tipoctg',
            'descripcionctg',
            'unidadctg'
        );
    

        session()->forget('servicio-sucursal');
        session()->forget('servicio-memo');
        $this->listaForaneosguarda = [];
        $this->dataforaneo = [];
        $this->editarPreciohabilitado = false;
        $this->precioUnitario = 0;
        $this->cantidadhabilitado = false;
        $this->totalreal = 0;
        $this->data = [];
    }
}
