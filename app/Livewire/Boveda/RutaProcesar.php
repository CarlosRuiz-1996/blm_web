<?php

namespace App\Livewire\Boveda;

use App\Livewire\Forms\BovedaForm;
use App\Models\BancosServicioAcreditacion;
use App\Models\Cliente;
use App\Models\ClienteMontos;
use App\Models\CompraEfectivo;
use App\Models\DetallesCompraEfectivo;
use App\Models\Inconsistencias;
use App\Models\MontoBlm;
use App\Models\Reprogramacion;
use App\Models\Ruta;
use App\Models\RutaCompraEfectivo;
use App\Models\RutaEmpleadoReporte;
use App\Models\RutaEmpleados;
use App\Models\RutaServicio;
use App\Models\RutaServicioReporte;
use App\Models\RutaVehiculo;
use App\Models\RutaVehiculoReporte;
use App\Models\ServicioComision;
use App\Models\ServicioKey;
use App\Models\ServicioPuerta;
use App\Models\ServicioRutaEnvases;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class RutaProcesar extends Component
{
    public $ruta;
    public BovedaForm $form;
    public $readyToLoad = false;
    public function loadServicios()
    {
        $this->readyToLoad = true;
    }
    public $puertas;
    public $comisiones;
    public function mount(Ruta $ruta)
    {

        $this->ruta = $ruta;
        $this->comisiones = collect(); // Inicializar como colección vacía
        $this->puertas = collect();
        $ruta_serv = RutaServicio::where('ruta_id', $ruta->id)->get();
        foreach ($ruta_serv as $serv) {
            $this->comisiones = $this->comisiones->merge(ServicioComision::where('ruta_servicio_id', $serv->id)->get());
            $this->puertas = $this->puertas->merge(ServicioPuerta::where('ruta_servicio_id', $serv->id)
                ->where('status_puerta_servicio', '!=', 4)
                ->get());
        }
    }
    public function render()
    {
        return view('livewire.boveda.ruta-procesar');
    }

    public $monto_calculado = 0;
    public $monto_envases = [];
    public $servicio_e = [];
    public $inconsistencia = 0;
    public $diferencias = [];
    public $observaciones = [];
    public function opernModal($servicio_id)
    {
        $this->dispatch('limpiar_monto_js');
        $this->limpiar();
        $servicio = ServicioRutaEnvases::where('ruta_servicios_id', $servicio_id)->where('status_envases', 1)->get();
        foreach ($servicio as $s) {
            $this->form->servicio = $s->rutaServicios;
            $this->form->folio = $this->form->servicio->id;
            $this->form->papeleta = $this->form->servicio->folio ?? 'Sin especificar';
            $this->form->monto = $this->form->servicio->monto;

            $this->monto_envases[$s->id] = [
                'cantidad' => 0,
                'acreditacion' => false
            ];
        }
        $this->servicio_e = $servicio;
        // dd($this->monto_envases);



    }



    public function validar()
    {

        $this->validate([
            'monto_envases.*.cantidad' => 'required|numeric|min:1',
        ], [
            'monto_envases.*.cantidad.required' => 'La cantidad es obligatoria',
            'monto_envases.*.cantidad.numeric' => 'La cantidad debe ser un número',
            'monto_envases.*.cantidad.min' => 'La cantidad no debe ser al menos 0',
        ]);


        try {
            DB::beginTransaction();

            $monto_total_envases = 0;

            //reviso so hay diferencia de valores
            foreach ($this->servicio_e as $s) {
                if (isset($this->monto_envases[$s->id])) {
                    $input = $this->monto_envases[$s->id];

                    // Verificar si la cantidad coincide
                    if ($s->cantidad == $input['cantidad']) {
                        $monto_total_envases += $input['cantidad'];
                    }
                }
            }
            $diferencia = "";
            //cuando hay diferencia de valores
            if ($this->form->monto != $monto_total_envases) {
                foreach ($this->servicio_e as $s) {

                    foreach ($this->monto_envases as $index => $input) {
                        $tipo_dif = 1;

                        if ($s->id == $index) {
                            if ($s->cantidad != $input['cantidad']) {

                                $diferencia = $input['cantidad'] - $s->cantidad;
                                if ($input['cantidad'] < $s->cantidad) {
                                    $tipo_dif = 0; //menor
                                    $diferencia = $s->cantidad - $input['cantidad'];
                                }
                                $this->diferencias[] = [
                                    'servicio' => $s,
                                    'cantidad_ingresada' => $input['cantidad'],
                                    'diferencia' => $diferencia,
                                    'tipo_dif' => $tipo_dif,
                                    'monto' => $s->cantidad
                                ];
                                $this->inconsistencia = 1;
                            }
                        }
                    }
                }
                throw new \Exception('Existe una diferencia  si continuas se generará el acta administrativa  de diferencias, Deseas continuar...');
            }
            $this->finalizar_recolecta();
            $this->dispatch('agregarArchivocre', ['msg' => 'El servicio de recolecta ha sido termiando'], ['tipomensaje' => 'success']);
            $this->limpiar();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('No se pudo completar la solicitud: ' . $e->getMessage());

            if ($this->inconsistencia == 0) {
                $this->dispatch('error', [$e->getMessage()]);
            } else {
                $this->dispatch('diferencia', [$e->getMessage()]);
            }


            Log::info('Info: ' . $e);
        }
    }

    #[On('finaliza-entrega')]
    public function finaliza_entrega(RutaServicio $servicio)
    {

        try {
            DB::beginTransaction();

            //actualizar la informacion de ruta servicio
            $servicio->status_ruta_servicios = 5;
            $servicio->save();

            //registra movimiento en el historial
            ClienteMontos::create([
                'cliente_id' => $servicio->servicio->cliente->id,
                'monto_old' => $servicio->servicio->cliente->resguardo,
                'monto_in' => $servicio->monto,
                'monto_new' => $servicio->servicio->cliente->resguardo - $servicio->monto,
                'empleado_id' => Auth::user()->empleado->id,
                'ctg_area_id' => Auth::user()->empleado->ctg_area_id,
                'tipo' => 0

            ]);

            //descontar
            $servicio->servicio->cliente->resguardo = $servicio->servicio->cliente->resguardo - $servicio->monto;
            $servicio->servicio->cliente->save();

            //actualizar la informacion de envases
            $servicio_envase =  ServicioRutaEnvases::where('ruta_servicios_id', $servicio->id)->where('status_envases', 1)->get();
            foreach ($servicio_envase as $envase) {
                $envase->status_envases = 2;
                $envase->save();
                $envase->evidencia_entrega->status_evidencia_entrega = 2;
                $envase->evidencia_entrega->save();
            }

            $this->finalizarReprogramacion($servicio);
            $this->limpiar();
            $this->dispatch('agregarArchivocre', ['msg' => 'El servicio de entrega ha sido termiando'], ['tipomensaje' => 'success']);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('No se pudo completar la solicitud: ' . $e->getMessage());

            $this->dispatch('error', ['Hubo un error, intenta mas tarde.']);
        }
    }

    #[On('terminar-ruta-boveda')]
    public function TerminarRuta()
    {

        try {
            DB::beginTransaction();
            $serviciosPendientes = RutaServicio::where('ruta_id', $this->ruta->id)
                ->whereBetween('status_ruta_servicios', [1, 4])
                ->count();
            $comprasPendientes = RutaCompraEfectivo::where('ruta_id', $this->ruta->id)
                ->where('status_ruta_compra_efectivos', 3)->count();



            $keys = RutaServicio::where('ruta_id', $this->ruta->id)->where('status_ruta_servicios', '!=', 0)
                ->whereHas('keys', function ($query) {
                    $query->where('status_servicio_keys', 1);
                })
                ->count();


            $servicioPuertaCount = ServicioPuerta::whereHas('rutaServicio', function ($query) {
                $query->where('ruta_id', $this->ruta->id)
                    ->where('puerta', 1);
            })->where('status_puerta_servicio', 3)
                ->count();

            $rutaServicioCount = RutaServicio::where('ruta_id', $this->ruta->id)
                ->where('puerta', 1)
                ->where('status_ruta_servicios', 5)
                ->count();


            if ($keys > 0) {
                throw new \Exception('No se puede terminar la ruta porque aún tiene Llaves por entregar.');
            }
            if ($serviciosPendientes > 0) {
                throw new \Exception('No se puede terminar la ruta porque aún tiene servicios pendientes.');
            }

            if ($comprasPendientes > 0) {
                throw new \Exception('No se puede terminar la ruta porque aún tiene compras de efectivo pendientes.');
            }

            if ($servicioPuertaCount != $rutaServicioCount) {
                throw new \Exception('No se puede terminar la ruta porque aún tiene servicio de puerta en puerta pendientes.');
            }
            //cambio a 5 su estatus de las compras
            RutaCompraEfectivo::where('ruta_id', $this->ruta->id)
                ->where('status_ruta_compra_efectivos', 4)
                ->update(['status_ruta_compra_efectivos' => 5]);


            // Si no hay servicios pendientes con estado 2, actualiza el estado de la ruta
            $this->ruta->status_ruta = 1;
            $this->ruta->ctg_rutas_estado_id = 1;
            $this->ruta->save();


            //termino los servicios
            RutaServicio::where('ruta_id', $this->ruta->id)
                ->where('status_ruta_servicios', 5)->update(['status_ruta_servicios' => 6]);

            if (!$this->puertas->isEmpty()) {
                $serv = RutaServicio::where('ruta_id', $this->ruta->id)->where('puerta', 1)->get();
                ServicioPuerta::where('ruta_servicio_id', $serv->id)
                    ->where('status_puerta_servicio', 3)->update(['status_puerta_servicio' => 4]);
            }

            //actualizo ruta vehiculo
            RutaVehiculo::where('ruta_id', $this->ruta->id)->where('status_ruta_vehiculos', 2)->update(['status_ruta_vehiculos' => 1]);
            //actualizar empleados
            RutaEmpleados::where('ruta_id', $this->ruta->id)->where('status_ruta_empleados', 2)->update(['status_ruta_empleados' => 1]);


            //actualiza el reportes:
            RutaServicioReporte::where('ruta_id', $this->ruta->id)
                // ->where('tipo_servicio', $this->form->servicio->tipo_servicio)
                ->update(['status_ruta_servicio_reportes' => 0]);
            RutaVehiculoReporte::where('ruta_id', $this->ruta->id)
                ->where('status_ruta_vehiculo_reportes', 2)->update(['status_ruta_vehiculo_reportes' => 1]);
            RutaEmpleadoReporte::where('ruta_id', $this->ruta->id)
                ->where('status_ruta_empleado_reportes', 2)->update(['status_ruta_empleado_reportes' => 0]);
            // Envía un mensaje de éxito
            $this->dispatch('agregarArchivocre', ['msg' => 'La ruta ha sido terminada'], ['tipomensaje' => 'success'], ['terminar' => 1]);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('No se pudo completar la solicitud: ' . $e->getMessage());

            $this->dispatch('error', [$e->getMessage() ?? 'Hubo un error, intenta mas tarde.']);
        }
    }

    #[On('clean')]
    public function limpiar()
    {
        $this->monto_envases = [];
        $this->form->servicio = "";
        $this->form->folio = "";
        $this->form->monto = "";
        $this->monto_calculado = 0;
        $this->inconsistencia = 0;
        $this->diferencias = [];
        $this->servicio_e = [];
        $this->observaciones = [];
    }
    #[On('corregirMonto')]
    public function corregirMonto()
    {
        $this->monto_envases = [];

        $this->monto_calculado = 0;
    }



    public function diferencia()
    {

        try {
            DB::beginTransaction();
            foreach ($this->diferencias as $index => $diferencia) {

                //insertar una inconsistencia de valores
                Inconsistencias::create(
                    [
                        'cliente_id' => $diferencia['servicio']['rutaServicios']['servicio']['cliente_id'],
                        'ruta_servicio_reportes_id' => $this->form->servicio->id,
                        'fecha_comprobante' => $diferencia['servicio']['updated_at'],
                        'folio' => $diferencia['servicio']['folio'],
                        'importe_indicado' => $diferencia['monto'],
                        'importe_comprobado' => $diferencia['cantidad_ingresada'],
                        'diferencia' => $diferencia['diferencia'],
                        'tipo' => $diferencia['tipo_dif'],
                        'observacion' => $this->observaciones[$index] ?? '',
                        'sello_seguridad' => $diferencia['servicio']['sello_seguridad']
                    ]
                );
            }

            $this->finalizar_recolecta();

            $this->limpiar();
            $this->dispatch('agregarArchivocre', ['msg' => 'El servicio de recoleccion ha sido termiando, los envases con diferencia estan en revision y los envases correctos fueron sumados a la cuenta del cliente.'], ['tipomensaje' => 'success']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
            Log::info('Info: ' . $e);

            $this->dispatch('error', ['No se pudo completar la solicitud, intenta mas tarde.']);
        }
    }

    public function finalizar_recolecta()
    {

        $servicio_envase =  ServicioRutaEnvases::where('ruta_servicios_id', $this->form->servicio->id)->where('status_envases', 1)->get();
        $monto_diferencia = 0;
        //actualizar la informacion de envases
        foreach ($servicio_envase as $envase) {
            $envase->status_envases = 2;
            foreach ($this->diferencias as $index => $diferencia) {
                if ($envase->id == $diferencia['servicio']['id']) {
                    $envase->status_envases = 0;
                    $monto_diferencia += $envase->cantidad;
                }
            }
            $envase->save();
            $envase->evidencia_recolecta->status_evidencia_recolecta = 2;
            $envase->evidencia_recolecta->save();

            //acreditacion....
            if ($this->monto_envases[$envase->id]['acreditacion']) {
                BancosServicioAcreditacion::create([
                    'servicios_envases_ruta_id' => $envase->id,
                ]);

                if ($envase->status_envases != 0) {
                    $monto_diferencia += $envase->cantidad;
                }
            }
        }

        $monto_in = $this->form->servicio->monto - $monto_diferencia;
        $monto_new = $this->form->servicio->servicio->cliente->resguardo + $monto_in;
        //registra movimiento en el historial
        ClienteMontos::create([
            'cliente_id' => $this->form->servicio->servicio->cliente->id,
            'monto_old' => $this->form->servicio->servicio->cliente->resguardo,
            'monto_in' => $monto_in,
            'monto_new' => $monto_new,
            'empleado_id' => Auth::user()->empleado->id,
            'ctg_area_id' => Auth::user()->empleado->ctg_area_id,
        ]);

        $this->finalizarReprogramacion($this->form->servicio);
        //actualizar la informacion de ruta servicio
        $this->form->servicio->status_ruta_servicios = 5;
        $this->form->servicio->save();
        //actualiza monto
        $this->form->servicio->servicio->cliente->resguardo = $monto_new;
        $this->form->servicio->servicio->cliente->save();
    }


    // detalles de la comrpa
    public $readyToLoadModal = false;

    public $compra_detalle;
    public $status_compra;
    public function showCompraDetail(CompraEfectivo $compra)
    {

        $this->compra_detalle = $compra;
        $this->readyToLoadModal = true;

        $this->status_compra = ($this->compra_detalle->status_compra_efectivos);
    }
    public function limpiarDatos()
    {
        $this->reset(
            'readyToLoadModal',
            'compra_detalle',
            'status_compra',
            'evidencia_foto',
            'papeleta',
            'MontoEntrega',
            'servicioRuta_id',
            'inputs'
        );
    }
    #[On('finaliza-compra')]
    public function finalizaCompra(RutaCompraEfectivo $ruta_compra)
    {
        try {

            DB::beginTransaction();
            $ruta_compra->status_ruta_compra_efectivos = 4; //finalizada
            $ruta_compra->save();

            $ruta_compra->compra->status_compra_efectivos = 4; //finalizada la compra efectivo por boveda
            $ruta_compra->compra->save();

            //agrego al monto total de blm
            MontoBlm::find(1)->increment('monto', $ruta_compra->compra->total);
            $this->dispatch(
                'agregarArchivocre',
                ['msg' => 'La compra de efectivo se finalizo.'],
                ['tipomensaje' => 'success']
            );
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch(
                'agregarArchivocre',
                ['msg' => 'Hubo un error intenta mas tarde.'],
                ['tipomensaje' => 'success']
            );
        }
    }
    public  $evidencia_foto;
    public function evidenciaRecolecta(ServicioRutaEnvases $envase)
    {
        $this->evidencia_foto =  'evidencias/EntregasRecolectas/Servicio_' . $envase->ruta_servicios_id . '_recolecta_' . $envase->evidencia_recolecta->id . '_evidencia.png';
        $this->readyToLoadModal = true;
    }
    public function evidenciaCompra(DetallesCompraEfectivo $detalle)
    {
        $this->evidencia_foto =  'evidencias/CompraEfectivo/compra_efectivo_detalle_' . $detalle->envase->id . '.png';
        $this->readyToLoadModal = true;
    }
    // detalles entrega
    public $papeleta;
    public $MontoEntrega;
    public $servicioRuta_id;
    public $inputs = [];
    public function detallesEntrega(RutaServicio $servicioRuta)
    {
        $this->readyToLoadModal = true;

        $this->papeleta = $servicioRuta->folio;
        $this->MontoEntrega = $servicioRuta->monto;
        $this->servicioRuta_id = $servicioRuta->id;

        $serviciosEnvases = ServicioRutaEnvases::where('ruta_servicios_id', $servicioRuta->id)->where('status_envases', 1)->get();

        // Si hay registros, llenar los arreglos con los valores recuperados
        if ($serviciosEnvases->isNotEmpty()) {
            // dd('entra');
            $this->inputs = $serviciosEnvases->mapWithKeys(function ($item) {
                return [$item->id => [
                    'cantidad' => $item->cantidad,
                    'folio' => $item->folio,
                    'sello' => $item->sello_seguridad,
                ]];
            })->toArray();
        }
    }

    public function evidenciaEntrega(ServicioRutaEnvases $ruta_servicio)
    {


        $this->evidencia_foto =  'evidencias/EntregasRecolectas/Servicio_' . $ruta_servicio->ruta_servicios_id .
            '_entrega_' . $ruta_servicio->evidencia_entrega->id . '_evidencia.png';
        $this->readyToLoadModal = true;
    }

    public $keys;
    public $ruta_servicio;
    public function showKeys(RutaServicio $rutaServicio)
    {
        $this->ruta_servicio = $rutaServicio;
        $this->keys = ServicioKey::where('ruta_servicio_id', $rutaServicio->id)->get();
    }

    public function updateKeys(ServicioKey $key)
    {
        $key->status_servicio_keys = 2;
        $key->save();
        $this->keys = ServicioKey::where('ruta_servicio_id', $key->ruta_servicio_id)->get();
    }
    public function cleanKeys()
    {

        $this->reset('keys', 'ruta_servicio');
    }

    public function endKeysRutaServices()
    {
        $keys = ServicioKey::where('ruta_servicio_id', $this->ruta_servicio->id)->where('status_servicio_keys', 1)->count();
        if ($keys == 0) {
            $this->ruta_servicio->keys = 2;
            $this->ruta_servicio->save();
            $this->dispatch('agregarArchivocre', ['msg' => 'Las llaves del servicio fueron entregados'], ['tipomensaje' => 'success']);
        } else {
            $this->dispatch('agregarArchivocre', ['msg' => 'Aun faltan por entregar llaves'], ['tipomensaje' => 'error']);
        }
    }


    public function finalizarReprogramacion(RutaServicio $serv)
    {
        Reprogramacion::where('ruta_servicio_id', $serv->id)->update(['status_reprogramacions' => 3]);
    }

    //comisiones

    public function evidenciaComision(ServicioComision $comision)
    {
        $this->evidencia_foto =  'evidencias/ComisionesServicios/comision_' . $comision->id . '_evidencia.png';
        $this->readyToLoadModal = true;
    }
    public $comision;
    public $comision_cliente;
    public $comision_sucursal;
    public $comision_papeleta;
    public $comision_monto;

    public function montoComision(ServicioComision $comision)
    {
        $this->comision = $comision;

        $this->comision_cliente = $comision->ruta_servicio->servicio->cliente->razon_social;
        $this->comision_sucursal = $comision->ruta_servicio->servicio->sucursal->sucursal->sucursal;
        $this->comision_papeleta = $comision->papeleta;
        $this->comision_monto = $comision->monto;

        $this->readyToLoadModal = true;
    }

    public function cleanComision()
    {
        $this->reset('comision', 'comision_cliente', 'comision_sucursal', 'comision_papeleta', 'comision_monto', 'readyToLoadModal');
    }

    public function editMontoComision()
    {
        $this->validate(
            ['comision_monto' => 'required|not_in:0'],
            [
                'comision_monto.required' => 'El monto es requerido',
                'comision_monto.not_in' => 'No puede ser el monto en 0',
            ]
        );

        try {
            DB::transaction(function () {
                $this->comision->monto = $this->comision_monto;
                $this->comision->save();
            });
            $this->cleanComision();
            $this->dispatch('agregarArchivocre', ['msg' => 'El monto se ha modificado'], ['tipomensaje' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('error', ['Hubo un error, intenta mas tarde.']);
        }
    }
    public function endComision(ServicioComision $comision)
    {
        try {
            DB::transaction(function () use ($comision) {
                if ($comision->monto == 0) {
                    throw new \Exception('No se puede finalizar la comision con el monto en 0');
                }
                $comision->status_servicio_comisions = 2;
                $comision->save();
            });

            $this->mount($this->ruta);
            $this->cleanComision();
            $this->dispatch('agregarArchivocre', ['msg' => 'La comision fue aprobada'], ['tipomensaje' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('error', [$e->getMessage()]);
        }
    }

    //puerta
    public function endPuerta(RutaServicio $servicio)
    {
        try {
            DB::beginTransaction();

            //actualizar la informacion de ruta servicio
            $servicio->status_ruta_servicios = 5;
            $servicio->save();
            //finaliza puerta
            $servicio->puertaHas->status_puerta_servicio = 3;
            $servicio->puertaHas->save();
            //actualizar la informacion de envases
            $servicio_envase =  ServicioRutaEnvases::where('ruta_servicios_id', $servicio->id)->where('status_envases', 1)->get();
            foreach ($servicio_envase as $envase) {
                $envase->status_envases = 2;
                $envase->save();
                $envase->evidencia_entrega->status_evidencia_entrega = 2;
                $envase->evidencia_entrega->save();
            }

            $this->limpiar();
            $this->dispatch('agregarArchivocre', ['msg' => 'El servicio de entrega ha sido termiando'], ['tipomensaje' => 'success']);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('No se pudo completar la solicitud: ' . $e->getMessage());

            $this->dispatch('error', ['Hubo un error, intenta mas tarde.']);
        }
    }


    public function detallesPuerta(RutaServicio $servicioRuta)
    {
        $this->readyToLoadModal = true;

        $this->papeleta = $servicioRuta->folio;
        $this->MontoEntrega = $servicioRuta->monto;
        $this->servicioRuta_id = $servicioRuta->id;

        $serviciosEnvases = ServicioRutaEnvases::where('ruta_servicios_id', $servicioRuta->id)->get();

        // ->where('status_envases', 1)


        // Si hay registros, llenar los arreglos con los valores recuperados
        if ($serviciosEnvases->isNotEmpty()) {
            // dd('entra');
            $this->inputs = $serviciosEnvases->mapWithKeys(function ($item) {
                return [$item->id => [
                    'cantidad' => $item->cantidad,
                    'folio' => $item->folio,
                    'sello' => $item->sello_seguridad,
                ]];
            })->toArray();
        }
    }

    public function evidenciaPuerta(ServicioRutaEnvases $ruta_servicio, $op)
    {


        $tipo = $op == 1 ? '_recolecta_' : '_entrega_';
        $this->evidencia_foto =  'evidencias/PuertaEnPuerta/Puerta_' . $ruta_servicio->id . $tipo .
            $ruta_servicio->evidencia_entrega->id . '_evidencia.png';
        $this->readyToLoadModal = true;
    }
}
