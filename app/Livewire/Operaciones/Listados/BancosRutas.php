<?php

namespace App\Livewire\Operaciones\Listados;

use App\Models\BancosServicios;
use App\Models\CompraEfectivo;
use App\Models\CtgRutaDias;
use App\Models\Ruta;
use App\Models\RutaCompraEfectivo;
use App\Models\RutaServicio;
use App\Models\Servicios;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class BancosRutas extends Component
{
    use WithPagination;
    public $readyToLoad = false;
    public $ctg_ruta_dia_id;
    public $ruta_id;
    public function render()
    {
        if ($this->readyToLoad) {

            $servicio_bancos = BancosServicios::orderBy('id', 'DESC')->paginate();
            $compras = CompraEfectivo::orderBy('id', 'DESC')->paginate(); //where('status_compra_efectivos', 1)->
            $dias = CtgRutaDias::all();
        } else {
            $servicio_bancos = [];
            $compras = [];
            $dias = [];
        }
        return view('livewire.operaciones.listados.bancos-rutas', compact('servicio_bancos', 'compras', 'dias'));
    }

    public function loadBancos()
    {
        $this->readyToLoad = true;
    }

    public $compra;
    public function asignarCompraEfectivo(CompraEfectivo $compra)
    {
        $this->compra = $compra;
    }
    public $rutas_dia = [];
    public function updating($property, $value)
    {
        if ($property === 'ctg_ruta_dia_id') {

            if ($value != "") {
                $this->resetValidation('ctg_ruta_dia_id');
                $this->rutas_dia = Ruta::where('ctg_ruta_dia_id', '=', $value)->get();

                $this->ruta_id = "";
            } else {

                $this->addError('ctg_ruta_dia_id', 'La fecha de evaluación debe ser menor a la fecha de inicio de servicio.');

                $this->ruta_id = "";
            }
        }
    }

    #[On('banco-compra-rutas')]
    public function addCompraRuta()
    {
        $this->validate(
            [
                'ctg_ruta_dia_id' => 'required',
                'ruta_id' => 'required',
            ],
            [
                'ctg_ruta_dia_id.required' => 'El dia es obligatorio.',
                'ruta_id.required' => 'La ruta es obligatoria.',
            ]
        );

        try {
            DB::beginTransaction();

            //asigno a una ruta.
            RutaCompraEfectivo::create([
                'ruta_id' => $this->ruta_id,
                'compra_efectivo_id' => $this->compra->id,
            ]);

            //actuializo el status de la compra.
            $this->compra->status_compra_efectivos = 2;
            $this->compra->save();


            DB::commit();
            $this->clean();
            $this->dispatch('alert', ['La compra de efectivo se realizara en la ruta seleccionada', 'success']);
        } catch (Exception $e) {

            DB::rollBack();
            $this->dispatch('alert', ['Ha ocurrido un error, intenta más tarde', 'error']);
        }
    }

    public function clean()
    {
        $this->reset('ruta_id', 'ctg_ruta_dia_id', 'compra', 'rutas_dia', 'banco_servicio');
    }


    //agregar servicio a rutas 
    public $banco_servicio;
    public function findRutaServicio(BancosServicios $servicio)
    {
        $this->banco_servicio = $servicio;
    }

    #[On('banco-servicio-rutas')]
    public function addServicioRuta()
    {

        $this->validate(
            [
                'ctg_ruta_dia_id' => 'required',
                'ruta_id' => 'required',
            ],
            [
                'ctg_ruta_dia_id.required' => 'El dia es obligatorio.',
                'ruta_id.required' => 'La ruta es obligatoria.',
            ]
        );

        try {
            DB::beginTransaction();

            if ($this->banco_servicio->servicio->ruta_servicio) {


                if ($this->banco_servicio->servicio->ruta_servicio->status_ruta_servicios == 1) {
                    RutaServicio::where('servicio_id', $this->banco_servicio->servicio->id)->update([
                        'ruta_id' => $this->ruta_id,
                        'monto' => $this->banco_servicio->monto,
                        'folio' => $this->banco_servicio->papeleta,
                        'tipo_servicio' => $this->banco_servicio->tipo_servicio,
                        'status_ruta_servicios' => 1,
                        'envase_cargado' => 0
                    ]);
                } else {
                    throw new \Exception('El servicio esta en ruta y no se puede modificar hasta que termine.');
                }
            } else {
                RutaServicio::create([
                    'servicio_id' => $this->banco_servicio->servicio_id,
                    'ruta_id' => $this->ruta_id,
                    'monto' => $this->banco_servicio->monto,
                    'folio' => $this->banco_servicio->papeleta,
                    'tipo_servicio' => $this->banco_servicio->tipo_servicio,

                ]);
            }

            $this->banco_servicio->status_bancos_servicios = 2;
            $this->banco_servicio->save();
            DB::commit();
            $this->clean();
            $this->dispatch('alert', ['El servicio fue asignado a la ruta con exito', 'success']);
        } catch (Exception $e) {

            DB::rollBack();
            $this->dispatch('alert', [$e->getMessage(), 'error']);
        }
    }
}