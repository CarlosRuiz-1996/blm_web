<?php

namespace App\Livewire\Operaciones\Listados;

use App\Models\Reprogramacion as ModelsReprogramacion;
use App\Models\RutaServicio;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Forms\RutaForm;
use App\Models\Ruta;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Reprogramacion extends Component
{
    use WithPagination;
    public RutaForm $form;
    public $readyToLoad = false;
    public function render()
    {
        if($this->readyToLoad){
        $dias = $this->form->getCtgDias();
        // $reprogramacion = RutaServicio::where('status_ruta_servicios',0)->paginate(10);
        $reprogramacion = ModelsReprogramacion::orderBy('id', 'DESC')->paginate(10);

        }else{
            $dias = [];
            $reprogramacion = [];
        }
        return view('livewire.operaciones.listados.reprogramacion', compact('reprogramacion', 'dias'));
    }
    public function loadServicios()
    {
        $this->readyToLoad = true;
    }
    public $repro_detail;
    public function DetalleServicio(ModelsReprogramacion $repro)
    {
        $this->repro_detail = $repro;
        // dd($repro->ruta_servicio);
        $this->form->monto = '$ ' . number_format($repro->ruta_servicio->monto, 2, '.', ',') . 'MXN';
        $this->form->folio =  $repro->ruta_servicio->folio;
        $this->form->envases =  $repro->ruta_servicio->envases;
    }

    public $ctg_ruta_dia_id;
    public $rutas_dia;
    public function updating($property, $value)
    {
        if ($property === 'form.ctg_ruta_dia_id') {

            if ($value != "") {
                $this->resetValidation('form.ctg_ruta_dia_id');

                //traer las rutas dependiendo si es entrega o recoleccion
                $baseQuery = Ruta::where('ctg_ruta_dia_id', '=', $value)
                    ->where('id', '!=', $this->repro_detail->ruta_servicio->ruta_id);

                if ($this->repro_detail->ruta_servicio->tipo_servicio == 1) {
                    $baseQuery->where('status_ruta', '=', 1); //entrega
                } else {
                    $baseQuery->where('status_ruta', '<', 3); //recoleccion
                }

                $this->rutas_dia = $baseQuery->get();
            } else {

                $this->addError('form.ctg_ruta_dia_id', 'La fecha de evaluación debe ser menor a la fecha de inicio de servicio.');
            }
        }
    }

    #[On('save-reprogramacion-ruta')]
    public function save()
    {
        $this->validate([
            'form.ruta_id' => 'required',
            'form.ctg_ruta_dia_id' => 'required',
        ], [
            'form.ruta_id.required' => 'Ruta obligatoria',
            'form.ctg_ruta_dia_id.required' => 'Dia obligatoria',

        ]);
        try {
            DB::beginTransaction();
            $this->repro_detail->ruta_servicio_id_new = $this->form->ruta_id;
            $this->repro_detail->status_reprogramacions = 2;
            $this->repro_detail->save();
            $this->dispatch('alert', ['msg' => 'El servicio fue asignado correctamente.'], ['tipo' => 'success']);
            // $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'Entrega realizada con exito.'], ['tipomensaje' => 'success'], ['op' => 1]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', ['msg' => 'Ha ocurrido un error.'], ['tipo' => 'error']);
        }
    }
}
