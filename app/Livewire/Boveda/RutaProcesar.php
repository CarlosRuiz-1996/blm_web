<?php

namespace App\Livewire\Boveda;

use App\Livewire\Forms\BovedaForm;
use App\Models\Ruta;
use App\Models\RutaEmpleadoReporte;
use App\Models\RutaEmpleados;
use App\Models\RutaServicio;
use App\Models\RutaServicioReporte;
use App\Models\RutaVehiculo;
use App\Models\RutaVehiculoReporte;
use App\Models\ServicioRutaEnvases;
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

    public function opernModal(RutaServicio $servicio)
    {

        // foreach($servicio->envases_servicios as $n){
        //     if($n->tipo_servicio==2){
        //     dd($n->evidencia_recolecta);
        //     }
        // }

        $this->form->servicio = $servicio;
        $this->form->folio = $servicio->folio;
        $this->form->envases = $servicio->envases;
        $this->form->monto = $servicio->monto;

        $this->monto_envases = $servicio->envases_servicios->mapWithKeys(function ($item) {
            return [$item->id => [
                'cantidad' => 0,
            ]];
        })->toArray();

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

        $inconsistencia = 0;
        try {
            DB::beginTransaction();

            foreach ($this->monto_envases as $index => $input) {
                $this->monto_calculado  += (float)$input['cantidad'];
            }
            //si los montos son diferentes
            if ($this->monto_calculado != $this->form->monto) {
                $inconsistencia = 1;
                throw new \Exception('Los montos no coinsiden con lo que se indico. ¿Quiere generar una hoja de diferencia?');
            }

            //actualizar la informacion de ruta servicio
            $this->form->servicio->status_ruta_servicios = 1;
            $this->form->servicio->save();

            //actualizar la informacion de envases
            $this->form->servicio->envases_servicios->status_envases = 2;
            $this->form->servicio->envases_servicios->save();

            //actualizar la informacion de recolecta
            $this->form->servicio->envases_servicios->evidencia_recolecta->status_evidencia_recolecta = 2;
            $this->form->servicio->envases_servicios->evidencia_recolecta->save();
            $this->limpiar();
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'El servicio de recolecta ha sido termiando'], ['tipomensaje' => 'success']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('No se pudo completar la solicitud: ' . $e->getMessage());

            if ($inconsistencia == 0) {
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
            Log::info('Info: actualiza ruta_Servicio');
            $servicio->status_ruta_servicios = 1;
            $servicio->save();

            //actualizar la informacion de envases
            foreach($servicio->envases_servicios as $envase){
                Log::info('Info: actualiza envases');
                $envase->status_envases=2;
                $envase->save();
                Log::info('Info: actualiza evidencia');
                $envase->evidencia_entrega->status_evidencia_entrega = 2;
                $envase->evidencia_entrega->save();
            }
           

            $this->limpiar();
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'El servicio de entrega ha sido termiando'], ['tipomensaje' => 'success']);


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
                $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'No se puede terminar la ruta porque aún tiene servicios pendientes'], ['tipomensaje' => 'error']);
            } else {

                dd('mal validado');
                // Si no hay servicios pendientes con estado 2, actualiza el estado de la ruta
                $this->ruta->status_ruta = 1;
                $this->ruta->ctg_rutas_estado_id = 1;
                $this->ruta->save();


                //actualizo ruta vehiculo
                RutaVehiculo::where('ruta_id', $this->ruta->id)->where('status_ruta_vehiculos', 2)->update(['status_ruta_vehiculos' => 1]);

                //actualizar empleados
                RutaEmpleados::where('ruta_id', $this->ruta->id)->where('status_ruta_empleados', 2)->update(['status_ruta_empleados' => 1]);


                //actualiza el reportes:

                RutaServicioReporte::where('servicio_id', $this->form->servicio->servicio_id)
                    ->where('ruta_id', $this->ruta->id)
                    ->where('tipo_servicio', $this->form->servicio->tipo_servicio)
                    ->update(['status_ruta_servicio_reportes' => 0]);


                RutaVehiculoReporte::where('ruta_id', $this->ruta->id)
                    ->where('status_ruta_vehiculo_reportes', 2)->update(['status_ruta_vehiculo_reportes' => 1]);


                RutaEmpleadoReporte::where('ruta_id', $this->ruta->id)
                    ->where('status_ruta_empleado_reportes', 2)->update(['status_ruta_empleado_reportes' => 0]);
                // Envía un mensaje de éxito
                $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La ruta ha sido terminada'], ['tipomensaje' => 'success']);
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
        $this->monto_envases = [];
        $this->form->servicio = "";
        $this->form->folio = "";
        $this->form->envases = "";
        $this->form->monto = "";
        $this->monto_calculado = 0;
    }
}
