<?php

namespace App\Livewire\Catalogos;

use App\Models\CtgServicios;
use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;

class Servicios extends Component
{


    public $readyToLoad = false;
    use WithPagination, WithoutUrlPagination;
    public $search;
    public function loadTable()
    {
        $this->readyToLoad = true;
    }
    public function render()
    {
        if ($this->readyToLoad) {
            $datas =  CtgServicios::where('folio', 'ilike', '%' . $this->search . '%')
                ->orWhere('tipo', 'ilike', '%' . $this->search . '%')
                ->orWhere('descripcion', 'ilike', '%' . $this->search . '%')
                ->orWhere('unidad', 'ilike', '%' . $this->search . '%')
                ->orderBy('id', 'DESC')->paginate(10);;
        } else {
            $datas = [];
        }
        return view('livewire.catalogos.servicios', compact('datas'));
    }


    #[On('save-data')]
    public function save()
    {

        DB::beginTransaction();

        try {
            $this->validate();
            CtgServicios::create([
                'folio'=>$this->folio,
                'tipo'=>$this->tipo,
                'descripcion'=>$this->descripcion,
                'unidad'=>$this->unidad,
            ]);
            DB::commit();
            $this->reset('folio','tipo','descripcion','unidad');
            $this->dispatch('success-data', "El registro se agrego al catalogo.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }

    public $data_id = 0;
    public $data;

    #[Validate('required')]
    public $folio;
    public $tipo;
    public $descripcion;
    public $unidad;

    public function setData(CtgServicios $data)
    {
        $this->limpiar();
        $this->folio = $data->folio;
        $this->tipo = $data->tipo;
        $this->descripcion = $data->descripcion;
        $this->unidad = $data->unidad;
        $this->data_id = $data->id;
        $this->data = $data;

        $this->dispatch('edit-data');
    }

    public function limpiar()
    {
        $this->resetValidation();
        $this->folio = '';
        $this->tipo = '';
        $this->descripcion = '';
        $this->unidad = '';
        $this->data_id = 0;
        $this->data = '';
    }

    #[On('update-data')]
    public function update()
    {

        $this->validate();


        DB::beginTransaction();

        try {

            $data = CtgServicios::find($this->data_id);
            $data->update([
                'folio' => mb_strtoupper($this->folio),
                'tipo' => mb_strtoupper($this->tipo),
                'descripcion' => mb_strtoupper($this->descripcion),
                'unidad' => mb_strtoupper($this->unidad),
            ]);

            $this->reset('folio','tipo','descripcion','unidad');
            DB::commit();
            $this->dispatch('success-data', "El registro se actualizo con exito.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }

    #[On('delete-data')]
    public function delete(CtgServicios $data)
    {
        try {
            $data->delete();
            $this->dispatch('success-data', "El registro se dio de baja correctamente.");
        } catch (Exception $e) {
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }

    #[On('baja-data')]
    public function baja(CtgServicios $data)
    {

        try {
            $data->update([
                'status_servicio' => 0,
            ]);
            $this->dispatch('success-data', "El registro se dio de baja correctamente.");
        } catch (\Exception $e) {
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }

    #[On('delete-reactivar')]
    public function reactivar(CtgServicios $data)
    {
        try {
            $data->update([
                'status_servicio' => 1,
            ]);
            $this->dispatch('success-data', "El registro se restauro correctamente.");
        } catch (\Exception $e) {
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }
}
