<?php

namespace App\Livewire\Boveda;

use App\Livewire\Forms\BovedaForm;
use App\Models\Cliente;
use App\Models\ClienteMontos;
use App\Models\Inconsistencias;
use App\Models\Ruta;
use App\Models\RutaEmpleadoReporte;
use App\Models\RutaEmpleados;
use App\Models\RutaServicio;
use App\Models\RutaServicioReporte;
use App\Models\RutaVehiculo;
use App\Models\RutaVehiculoReporte;
use App\Models\ServicioRutaEnvases;
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
    public function mount(Ruta $ruta)
    {
        $this->ruta = $ruta;
    }
    public function render()
    {
        return view('livewire.boveda.ruta-procesar');
    }

    public $monto_calculado = 0;
    public $monto_envases = [];
    public $servicio_e = [];

    public function opernModal($servicio_id)
    {
        $this->limpiar();

        $servicio = ServicioRutaEnvases::where('ruta_servicios_id', $servicio_id)->where('status_envases', 1)->get();
        foreach ($servicio as $s) {
            $this->form->servicio = $s->rutaServicios;
            $this->form->folio = $this->form->servicio->folio;
            $this->form->monto = $this->form->servicio->monto;

            $this->monto_envases[$s->id] = ['cantidad' => 0];
        }
        $this->servicio_e = $servicio;
        // dd($this->monto_envases);



    }

    public $inconsistencia = 0;
    public $diferencias = [];

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
            Log::info('Info: entra a la transaccion');

            $tipo_dif=1;
            $diferencia="";
            foreach ($this->servicio_e as $s) {

                // dd($s);
                foreach ($this->monto_envases as $index => $input) {
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
            
                            throw new \Exception('Existe una diferencia  si continuas se generará el acta administrativa  de diferencias, Deseas continuar...');
                        }
                    }
                }
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
            $servicio->status_ruta_servicios = 1;
            $servicio->save();




            //registra movimiento en el historial
            ClienteMontos::create([
                'cliente_id' => $servicio->servicio->cliente->id,
                'monto_old' => $servicio->servicio->cliente->resguardo,
                'monto_in' => $servicio->monto,
                'monto_new' => $servicio->servicio->cliente->resguardo - $servicio->monto,
                'empleado_id' => Auth::user()->empleado->id,
                'ctg_area_id' => Auth::user()->empleado->ctg_area_id
            ]);

            //descontar
            $servicio->servicio->cliente->resguardo = $servicio->servicio->cliente->resguardo - $servicio->monto;
            $servicio->servicio->cliente->save();

            //actualizar la informacion de envases
            foreach ($servicio->envases_servicios as $envase) {
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
            // $this->dispatch('diferencia', [$e->getMessage()]);


            Log::info('Info: ' . $e);
        }
    }

    #[On('terminar-ruta-boveda')]
    public function TerminarRuta()
    {

        try {
            DB::beginTransaction();
            $serviciosPendientes = RutaServicio::where('ruta_id', $this->ruta->id)
                ->where('status_ruta_servicios', '>', 1)
                ->count();

            if ($serviciosPendientes > 0) {
                // Si hay servicios pendientes con estado 2, envía un mensaje de error
                $this->dispatch('agregarArchivocre', ['msg' => 'No se puede terminar la ruta porque aún tiene servicios pendientes'], ['tipomensaje' => 'error']);
            } else {

                // dd('mal validado');
                // Si no hay servicios pendientes con estado 2, actualiza el estado de la ruta
                $this->ruta->status_ruta = 1;
                $this->ruta->ctg_rutas_estado_id = 1;
                $this->ruta->save();

                Log::info('Info: actualiza vehiculo');

                //actualizo ruta vehiculo
                RutaVehiculo::where('ruta_id', $this->ruta->id)->where('status_ruta_vehiculos', 2)->update(['status_ruta_vehiculos' => 1]);
                Log::info('Info: actualiza empleados');

                //actualizar empleados
                RutaEmpleados::where('ruta_id', $this->ruta->id)->where('status_ruta_empleados', 2)->update(['status_ruta_empleados' => 1]);


                //actualiza el reportes:
                Log::info('Info: actualiza reportes servicio');

                RutaServicioReporte::where('ruta_id', $this->ruta->id)
                    // ->where('tipo_servicio', $this->form->servicio->tipo_servicio)
                    ->update(['status_ruta_servicio_reportes' => 0]);
                Log::info('Info: actualiza reportes servicio');

                Log::info('Info: actualiza reportes vehiculo');

                RutaVehiculoReporte::where('ruta_id', $this->ruta->id)
                    ->where('status_ruta_vehiculo_reportes', 2)->update(['status_ruta_vehiculo_reportes' => 1]);

                Log::info('Info: actualiza reportes empleado');

                RutaEmpleadoReporte::where('ruta_id', $this->ruta->id)
                    ->where('status_ruta_empleado_reportes', 2)->update(['status_ruta_empleado_reportes' => 0]);
                // Envía un mensaje de éxito
                $this->dispatch('agregarArchivocre', ['msg' => 'La ruta ha sido terminada'], ['tipomensaje' => 'success'], ['terminar' => 1]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('No se pudo completar la solicitud: ' . $e->getMessage());

            $this->dispatch('error', ['Hubo un error, intenta mas tarde.']);
            // $this->dispatch('diferencia', [$e->getMessage()]);


            Log::info('Info: ' . $e);
        }
    }

    #[On('clean')]
    public function limpiar()
    {
        // if ($this->inconsistencia == 0) {
        $this->monto_envases = [];
        $this->form->servicio = "";
        $this->form->folio = "";
        $this->form->monto = "";
        $this->monto_calculado = 0;
        // }
    }

    public $observaciones;
    #[On('inconsistencia')]
    public function inconsistencia()
    {

        try {
            DB::beginTransaction();
            //ruta servicio se pone en 0 para reprogramar
            $this->form->servicio->status_ruta_servicios = 1;
            $this->form->servicio->save();

            //actualizar la informacion de envases
            foreach ($this->form->servicio->envases_servicios as $envase) {
                $envase->status_envases = 0;
                $envase->save();
                $envase->evidencia_recolecta->status_evidencia_recolecta = 0;
                $envase->evidencia_recolecta->save();
            }

            Inconsistencias::create([
                'cliente_id' => $this->form->servicio->servicio->cliente->id,
                'ruta_servicio_reportes_id' => $this->form->servicio->id,
                'fecha_comprobante' => $this->form->servicio->updated_at,
                'folio' => $this->form->servicio->folio,
                'importe_indicado' => $this->form->servicio->monto,
                'importe_comprobado' => $this->monto_calculado,
                'diferencia' => $this->diferencia,
                'tipo' => $this->tipo_dif,
                'observacion' => $this->observaciones,

            ]);
            $this->dispatch('agregarArchivocre', ['msg' => 'El formato se genero con éxito.'], ['tipomensaje' => 'success']);

            DB::rollBack();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
            $this->dispatch('error', [$e->getMessage()]);
            Log::info('Info: ' . $e);
        }
    }

    public function finalizar_recolecta()
    {
        //actualizar la informacion de ruta servicio
        $this->form->servicio->status_ruta_servicios = 1;
        $this->form->servicio->monto = 0;
        $this->form->servicio->envases = 0;

        $this->form->servicio->save();

        //actualizar la informacion de envases
        foreach ($this->form->servicio->envases_servicios as $envase) {
            $envase->status_envases = 2;
            $envase->save();
            Log::info('Info: actualiza evidencia');
            $envase->evidencia_recolecta->status_evidencia_recolecta = 2;
            $envase->evidencia_recolecta->save();
        }

        //registra movimiento en el historial
        ClienteMontos::create([
            'cliente_id' => $this->form->servicio->servicio->cliente->id,
            'monto_old' => $this->form->servicio->servicio->cliente->resguardo,
            'monto_in' => $this->form->servicio->monto,
            'monto_new' => $this->form->servicio->servicio->cliente->resguardo + $this->form->servicio->monto,
            'empleado_id' => Auth::user()->empleado->id,
            'ctg_area_id' => Auth::user()->empleado->ctg_area_id
        ]);

        //descontar
        $this->form->servicio->servicio->cliente->resguardo = $this->form->servicio->servicio->cliente->resguardo + $this->form->servicio->monto;
        $this->form->servicio->servicio->cliente->save();
    }
}
