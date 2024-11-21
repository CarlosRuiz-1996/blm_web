<?php

namespace App\Livewire\Bancos;

use App\Exports\Bancos\Acreditaciones;
use App\Exports\Bancos\CompraEfectivoExport;
use App\Exports\Bancos\SaldoCliente;
use App\Exports\Bancos\ServiciosBancos;
use App\Models\Cliente;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Forms\BancosForm;
use App\Models\BancosServicioAcreditacion;
use App\Models\BancosServicios;
use App\Models\CompraEfectivo;
use App\Models\CtgConsignatario;
use App\Models\DetallesCompraEfectivo;
use App\Models\MontoBlm;
use App\Models\Servicios;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\WithoutUrlPagination;
use Maatwebsite\Excel\Facades\Excel;

/*revisar documentacion de livewire*/

class BancosGestion extends Component
{
    /* implementa paginacion*/
    use WithPagination,WithoutUrlPagination;

    /** implementa un objeto de formulario de bancos, el cual contienen logica de validacion y funciones para save y update, get, delete */
    public BancosForm $form;
    public $readyToLoad = false;
    public $readyToLoadModal = false;

    public $activeNav = ['active', '', '', ''];
    public $showNav = ['show', '', '', ''];


    //define una propiedad protegida, la cual sera ignorada por el url
    protected $queryString = [
        'form.searchCliente' => ['except' => ''],
    ];


    //funcion para controlar el refresh de livewire y mantrener activo el navBar en la vista,
    public function ActiveNav($op)
    {
        foreach ($this->showNav as $key => $value) {
            $this->showNav[$key] = '';
        }
        foreach ($this->activeNav as $key => $value) {
            $this->activeNav[$key] = '';
        }

        $this->activeNav[$op] = 'active';

        $this->showNav[$op] = 'show';
    }


    /**renderiza vista de livewire */
    public function render()
    {


        /***
         * readyToLoad, variable booleana que detecta si es la primera ves que entras a la pagina
         * si es la primera ves sera false, entoces la pagina se cargara sin realizar la consulta a los datos
         * la vista se envuelve con la funcion loadClientes, que se ejecutara una ves que cargue la pagina
         * posterior mente la pagina recargara y entrara a render pero como esta ves readyToLoad es true, ahora si buscara la informacion
         * posteriormente pasa la informacion a la vista livewire por medio de compact, funcioon de laravel.
         * 
         * la vista controla este funcionamiento con una condicion que indica que si readyToLoad es false muestra un spiner de lo contrario carga la informacion.
         */
        if ($this->readyToLoad) {
            $resguardototal = $this->form->getCountResguadoClientes();
            $resguardototalCliente = Cliente::where('status_cliente', 1)->sum('resguardo');

            $clientes = $this->form->getAllClientes();
            $servicios = $this->form->getAllBancosServicios();
            $compras = $this->form->getAllComprasEfectivo();
            $acreditaciones = $this->form->getAllAcreditaciones();
        } else {
            $resguardototal = 0;
            $resguardototalCliente = 0;
            $clientes = [];
            $servicios = [];
            $compras = [];
            $acreditaciones = [];
        }
        $clientes_activo = $this->form->getAllClientesActivo();
        $consignatarios = $this->form->getAllConsignatorio();
        return view('livewire.bancos.bancos-gestion', compact('acreditaciones', 'resguardototal', 'resguardototalCliente', 'clientes', 'servicios', 'compras', 'clientes_activo', 'consignatarios'));
    }
    public function loadClientes()
    {
        $this->readyToLoad = true;
    }

    /*cada que se actualiza la valirable form.searchCliente se renderiza la pagina y no se modifica la url.*/
    public function updatingFormSearchCliente()
    {
        $this->resetPage();
    }


    /***modifica las variables con informacion de un cliente especifico para mostrar su detalle en la vista */
    public function showMonto(Cliente $cliente)
    {
        $this->form->cliente = $cliente;
        $this->form->actual_monto = $cliente->resguardo;
    }
    public $cliente_detail;

    public function showDetail(Cliente $cliente)
    {
        $this->cliente_detail = $cliente;
    }

    public function getMontosProperty()
    {
        return $this->cliente_detail->montos()->orderBy('id', 'DESC')->paginate(10, pageName: 'montos');
    }


    /**funcion que escucha un evento desde la vista por medio de js para limpiar datos de las variables */

    #[On('clean')]
    public function limpiarDatos()
    {
        $this->reset(
            'form.cliente',
            'readyToLoadModal',
            'form.actual_monto',
            'form.nuevo_monto',
            'cliente_detail',
            'acreditacion_detail',
            'ticket'
        );
    }


    /*funcion que detecta cuando hay un cambio en el stage de la vista y revisa cual modelo cambio, en caso de que sea el monto se hace una opercaion de aumentar*/
    public function updating($property, $value)
    {

        if ($property === 'form.ingresa_monto') {
            if ($value != "") {
                $this->form->nuevo_monto =  $value + $this->form->cliente->resguardo;
            } else {
                $this->form->nuevo_monto = 0;
            }
        }
    }


    /**esta funcion implemnenta el objeto form y llama a una funcion para guardar la informacion, posteriormente dispara un evento a la vista que detecta por js.*/
    public function add()
    {
        $res =  $this->form->addMonto();

        if ($res == 1) {
            $this->dispatch('alert', ['Se modifico el monto del cliente.', 'success']);
            $this->render();
            $this->limpiarDatos();
        } else {

            $this->dispatch('alert', [$res == 0 ? 'Hubo un problema, intenta más tarde.' : $res, 'error']);
        }
    }

    //detalle compra
    public $compra_detalle = [];
    public function showCompraDetail(CompraEfectivo $compra)
    {
        $this->compra_detalle = $compra;
        $this->readyToLoadModal = true;
    }


    // servicios bancos

    public $cliente;
    public $servicio;
    public $monto_e = 0;
    public $cajero_id;
    public $fecha;
    public $total;
    public $tipo;
    public $papeleta;

    public $compras_efectivo = [];

    public function updatedMonto_e($value)
    {
        // Validar que el valor sea un número
        $this->validate([
            'monto_e' => 'numeric', // Solo números permitidos
        ]);
    }

    /**esta funcion agrega a un array dimencional informacion de las compras que se deben hacer
     * 
     * validate, realiza cierta logica de validacion para revisar que los dtaos sean correctos
     * CtgConsignatario::find--- entra a buscar un modelo del consignatario con eloquent
     * posterior agrega al array un array con datos de la compra para esperar a ser giuardados a la bd
     * se incrementa el total de la compra, variable global con el monto de la compra de efectivo
     * se resetean las variables.
     * 
     */
    public function addCompra()
    {
        $this->validate(
            [
                'cajero_id' => 'required',
                'monto_e' => 'required|numeric', // Agregamos la validación para que sea numérico
            ],
            [
                'cajero_id.required' => 'El cajero es obligatorio',
                'monto_e.required' => 'El monto es obligatorio',
                'monto_e.numeric' => 'El monto debe ser un número', // Mensaje personalizado para la validación numérica
            ]
        );
        $cajero = CtgConsignatario::find($this->cajero_id);
        $this->compras_efectivo[] = [
            "cajero" => $this->cajero_id,
            "cajero_name" => $cajero->name,
            "monto" => $this->monto_e,
        ];
        $this->total += $this->monto_e;
        $this->reset(['cajero_id', 'monto_e']);
    }


    /**elimina del array datos de una compra de efectivo antes de guardarlo en la db */
    public function removeCompra($index)
    {
        unset($this->compras_efectivo[$index]);
        $this->compras_efectivo = array_values($this->compras_efectivo); // Reindexar el array
        if (!count($this->compras_efectivo)) {
            $this->reset(['cajero_id', 'fecha']);
        }
    }

    public $servicios_cliente = [];
    //seleccionar servicios del cliente:

    public function updatedCliente($value)
    {
        if ($value != '')
            $this->servicios_cliente = Servicios::where('cliente_id', $value)->where('status_servicio', '>=', 3)->get();
    }


    /**se finaliza la compra y se guardan los datos del array mapeandolo y creanto detalles de compra.
     * 
     * se ocupa una transaccion de queryBuilder para poder controlar si hay algun error se realice un rollback
     * de lo contrario se realice un commit de la informacion.
     * 
     * se puede sustituir con:
     * DB::transaction(function(){//accionesn}.});
     * en esta ocuacion se realizo de esta forma para poder ver el proceso mas detallado del bloque de codigo
     */
    public function finalizarCompra()
    {

        $this->validate(
            [
                'fecha' => 'required',
            ],
            [
                'fecha.required' => 'La fecha es obligatorio',
            ]
        );

        try {
            DB::beginTransaction();

            if (!count($this->compras_efectivo)) {
                throw new \Exception('No hay servicios para guardar');
            }
            //guardar compra efectivo
            $compra_efectivo = CompraEfectivo::create([
                'total' => $this->total,
                'fecha_compra' => $this->fecha,
            ]);
            foreach ($this->compras_efectivo as  $compra) {
                DetallesCompraEfectivo::create([
                    'compra_efectivo_id' => $compra_efectivo->id,
                    'monto' => $compra['monto'],
                    'consignatario_id' => $compra['cajero'],
                ]);
            }
            $this->clean();
            $this->dispatch('alert', ['La compra de efectivo se mando a operaciones', 'success']);
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', [$e->getMessage(), 'error']);
        }
    }


    /*funcion que limpia las variables/modesl de livewire*/
    public function clean()
    {
        $this->form->reset([
            'cliente',
            'monto_e',
            'cajero_id',
            'fecha',
            'servicios_add',
            'servicio',
            'total',
            'compras_efectivo',
            'servicios_cliente',
            'tipo',
            'papeleta',
        ]);
    }

    public $direccion;
    public $servicios_add = [];
    public function addServicios()
    {


        $this->validate(
            [
                'cliente' => 'required',
                'servicio' => 'required',
                'papeleta' => 'required',
                'fecha' => 'required',
                'tipo' => 'required',
                'monto_e' => 'required_if:tipo,1', // Aquí se usa la regla condicional

            ],
            [
                'cliente.required' => 'El cliente es obligatorio',
                'monto_e.required_if' => 'El monto es obligatorio',
                'servicio.required' => 'El servicio es obligatorio',
                'papeleta.required' => 'La papeleta es obligatorio',
                'fecha.required' => 'La fecha es obligatorio',
                'tipo.required' => 'El tipo es obligatorio',
            ]
        );
        $cliente = Cliente::find($this->cliente);
        $servicio = Servicios::find($this->servicio);
        $this->servicios_add[] = [
            "cliente" => $this->cliente,
            "cliente_name" => $cliente->razon_social . '-' . $cliente->rfc_cliente,
            "monto" => $this->monto_e,
            "tipo_id" => $this->tipo,
            "tipo_servicio" => $this->tipo == 1 ? 'Entrega' : 'Recolecta',
            "fecha" => $this->fecha,
            "papeleta" => $this->papeleta,
            "servicio" => $this->servicio,
            "servicio_desc" => $servicio->ctg_servicio->descripcion,
            "direccion" => $this->direccion

        ];
        $this->reset(['cliente', 'monto_e', 'papeleta', 'servicio', 'tipo', 'fecha', 'direccion']);
        $this->dispatch('resetSelect2');
    }
    public function updatedServicio($value)
    {
        if ($value != '')
            $direccion = Servicios::find($value);
        $this->direccion = $direccion->sucursal ? $direccion->sucursal->sucursal->sucursal . ', ' . $direccion->sucursal->sucursal->direccion .
            ', ' .  $direccion->sucursal->sucursal->cp->cp .
            '' .
            $direccion->sucursal->sucursal->cp->estado->name : 'No tiene una sucursal definida aun.';
    }
    public function removeService($index)
    {
        unset($this->servicios_add[$index]);
        $this->servicios_add = array_values($this->servicios_add); // Reindexar el array

    }


    public function finalizarServicios()
    {
        try {
            DB::beginTransaction();

            if (!count($this->servicios_add)) {
                throw new \Exception('No hay servicios para guardar');
            }
            //guardar servicios desde bancos
            foreach ($this->servicios_add as  $servicio) {
                BancosServicios::create([
                    'servicio_id' => $servicio['servicio'],
                    'monto' => $servicio['monto'],
                    'papeleta' => $servicio['papeleta'],
                    'fecha_entrega' => $servicio['fecha'],
                    'tipo_servicio' => $servicio['tipo_id'],
                ]);
            }
            DB::commit();
            $this->clean();
            $this->dispatch('alert', ['Los servicios mandaron a operaciones', 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', [$e->getMessage(), 'error']);
        }
    }


    // acreditaciones
    public $acreditacion_detail;
    public $ticket;
    public function addTickect(BancosServicioAcreditacion $acreditacion)
    {
        $this->acreditacion_detail = $acreditacion;
    }

    public function finalizarAcreditacion()
    {
        $this->validate(['ticket' => 'required'], ['ticket.required' => 'El ticket es requerido']);

        try {
            DB::beginTransaction();

            $this->acreditacion_detail->folio = $this->ticket;
            $this->acreditacion_detail->status_acreditacion = 2;
            $this->acreditacion_detail->save();

            MontoBlm::find(1)->increment('monto', $this->acreditacion_detail->envase->cantidad);

            $this->dispatch('alert', ['El folio se guardo correctamente y el monto se sumo al monto total de blm.', 'success']);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', ['Hubo un problema, intenta más tarde.', 'error']);
        }
    }


    public function cleanFiltrerAcreditacion()
    {
        $this->form->monto_acreditacion_search = null;
        $this->form->papeleta_acreditacion_search = null;
        $this->form->fechai_acreditacion_search = null;
        $this->form->fechaf_acreditacion_search = null;
        $this->form->folio_acreditacion_search = null;
        $this->form->status_acreditacion_search = null;
    }

    public function cleanFiltrerDotaciones()
    {
        $this->form->cliente_bancoServ_serach = null;
        $this->form->papeleta_bancoServ_serach = null;
        $this->form->fechaini_bancoServ_serach = null;
        $this->form->fechafin_bancoServ_serach = null;
        $this->form->tipoServ_bancoServ_serach = null;
        $this->form->status_bancoServ_serach = null;
    }

    public function cleanFiltrerCompra()
    {
        $this->form->fechaini_compra_search = null;
        $this->form->fechafin_compra_search = null;
        $this->form->banco_compra_search = null;
        $this->form->monto_compra_search = null;
        $this->form->status_compra_search = null;
    }

    //expórtaciones a excel

    /**se realiza una consulta avanzada con eloquent para aplicar filtros antes de escargar el excel.
     * 
     * dichos filtros dependen de los campos del formulario/filtro de la vista.
     */
    public function exportarCompras()
    {
        $compras = CompraEfectivo::where(function ($query) {
            if ($this->form->monto_compra_search) {
                $query->where('total', 'ILIKE', '%' . $this->form->monto_compra_search . '%');
            }
            if ($this->form->status_compra_search) {
                $query->where('status_compra_efectivos',  $this->form->status_compra_search);
            }
            // Rango de fechas
            if ($this->form->fechaini_compra_search && $this->form->fechafin_compra_search) {
                $query->whereBetween('fecha_compra', [$this->form->fechaini_compra_search, $this->form->fechafin_compra_search]);
            } elseif ($this->form->fechaini_compra_search) {
                $query->where('fecha_compra', '=', $this->form->fechaini_compra_search);
            } elseif ($this->form->fechafin_compra_search) {
                $query->where('fecha_compra', '=', $this->form->fechafin_compra_search);
            }
            if ($this->form->banco_compra_search) {
                $query->orWhereHas('consignatario', function ($query) {
                    $query->where('name', 'ILIKE', '%' . $this->form->banco_compra_search . '%');
                });
            }
        })
            ->orderBy('id', 'DESC')
            ->get();
        return Excel::download(new CompraEfectivoExport($compras), 'compras.xlsx');
    }

    public function exportarSaldos()
    {

        $clientes =  Cliente::where('status_cliente', 1)
            ->where(function ($query) {
                $query->orWhere('razon_social', 'ilike', '%' . $this->form->searchCliente . '%')
                    ->orWhere('rfc_cliente', 'ilike', '%' . $this->form->searchCliente . '%')
                ;
            })->orderBy('id', 'ASC')
            ->get();

        return Excel::download(new SaldoCliente($clientes), 'saldo_clientes.xlsx');
    }

    public function exportarServicios()
    {

        $servicios = BancosServicios::where(function ($query) {

            if ($this->form->papeleta_bancoServ_serach) {
                $query->where('papeleta', 'ILIKE', '%' . $this->form->papeleta_bancoServ_serach . '%');
            }
            // Rango de fechas
            if ($this->form->fechaini_bancoServ_serach && $this->form->fechafin_bancoServ_serach) {
                $query->whereBetween('fecha_entrega', [$this->form->fechaini_bancoServ_serach, $this->form->fechafin_bancoServ_serach]);
            } elseif ($this->form->fechaini_bancoServ_serach) {
                $query->where('fecha_entrega', '=', $this->form->fechaini_bancoServ_serach);
            } elseif ($this->form->fechafin_bancoServ_serach) {
                $query->where('fecha_entrega', '=', $this->form->fechafin_bancoServ_serach);
            }

            if ($this->form->tipoServ_bancoServ_serach) {

                $query->where('tipo_servicio', $this->form->tipoServ_bancoServ_serach);
            }
            if ($this->form->status_bancoServ_serach) {
                $query->where('status_bancos_servicios', $this->form->status_bancoServ_serach);
            }

            if ($this->form->cliente_bancoServ_serach) {
                $query->orWhereHas('servicio', function ($query2) {
                    $query2->whereHas('cliente', function ($query3) {
                        $query3->where('razon_social', 'ILIKE', '%' . $this->form->cliente_bancoServ_serach . '%');
                    });
                });
            }
        })->orderBy('id', 'DESC')->get();
        return Excel::download(new ServiciosBancos($servicios), 'servicios_bancos.xlsx');
    }

    public function exportarAcreditacion()
    {

        $acreditaciones = BancosServicioAcreditacion::where(function ($query) {
            if ($this->form->folio_acreditacion_search) {
                $query->where('folio', 'ILIKE', '%' . $this->form->folio_acreditacion_search . '%');
            }
            if ($this->form->status_acreditacion_search) {
                $query->where('status_acreditacion',  $this->form->status_acreditacion_search);
            }
            // Rango de fechas
            if ($this->form->fechai_acreditacion_search && $this->form->fechaf_acreditacion_search) {
                $query->whereBetween('created_at', [$this->form->fechai_acreditacion_search, $this->form->fechaf_acreditacion_search]);
            } elseif ($this->form->fechai_acreditacion_search) {
                $query->where('created_at', '=', $this->form->fechai_acreditacion_search);
            } elseif ($this->form->fechaf_acreditacion_search) {
                $query->where('created_at', '=', $this->form->fechaf_acreditacion_search);
            }
            if ($this->form->monto_acreditacion_search) {
                $query->orWhereHas('envase', function ($query) {
                    $query->where('cantidad', 'ILIKE', '%' . $this->form->monto_acreditacion_search . '%');
                });
            }
            if ($this->form->papeleta_acreditacion_search) {
                $query->orWhereHas('envase', function ($query) {
                    $query->where('folio', 'ILIKE', '%' . $this->form->papeleta_acreditacion_search . '%');
                });
            }
            if ($this->form->cliente_acreditacion_search) {
                $query->orWhereHas('envase.rutaServicios.servicio.cliente', function ($query) {
                    $query->where('razon_social', 'ILIKE', '%' . $this->form->cliente_acreditacion_search . '%');
                });
            }
        })->orderBy('id', 'DESC')->get();
        return Excel::download(new Acreditaciones($acreditaciones), 'acreditaciones.xlsx');
    }
}
