<?php

namespace App\Livewire\Catalogos;

use App\Models\ctgTipoMoneda;
use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;
class TipoMoneda extends Component
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
            $datas =  ctgTipoMoneda::where('name', 'ilike', '%' . $this->search . '%')
                ->orderBy('id', 'DESC')->paginate(10);;
        } else {
            $datas = [];
        }
        return view('livewire.catalogos.tipo-moneda', compact('datas'));
    }


    #[On('save-data')]
    public function save()
    {

        DB::beginTransaction();

        try {
            $this->validate();
            ctgTipoMoneda::create($this->only(['name']));
            DB::commit();
            $this->reset('name');
            $this->dispatch('success-data', "El registro se agrego al catalogo.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }

    public $data_id = 0;
    public $data;

    #[Validate('required')]
    public $name;
    public function setData(ctgTipoMoneda $data)
    {
        $this->limpiar();
        $this->name = $data->name;
        $this->data_id = $data->id;
        $this->data = $data;

        $this->dispatch('edit-data');
    }

    public function limpiar()
    {
        $this->resetValidation();
        $this->name = '';
        $this->data_id = 0;
        $this->data = '';
    }

    #[On('update-data')]
    public function update()
    {

        $this->validate();


        DB::beginTransaction();

        try {

            $data = ctgTipoMoneda::find($this->data_id);
            $data->update([
                'name' => mb_strtoupper($this->name),
            ]);

            $this->reset('name');
            DB::commit();
            $this->dispatch('success-data', "El registro se actualizo con exito.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }

    #[On('delete-data')]
    public function delete(ctgTipoMoneda $data)
    {
        try {
            $data->delete();
            $this->dispatch('success-data', "El registro se dio de baja correctamente.");
        } catch (Exception $e) {
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }

    #[On('baja-data')]
    public function baja(ctgTipoMoneda $data)
    {

        try {
            $data->update([
                'status' => 0,
            ]);
            $this->dispatch('success-data', "El registro se dio de baja correctamente.");
        } catch (\Exception $e) {
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }

    #[On('delete-reactivar')]
    public function reactivar(ctgTipoMoneda $data)
    {
        try {
            $data->update([
                'status' => 1,
            ]);
            $this->dispatch('success-data', "El registro se restauro correctamente.");
        } catch (\Exception $e) {
            $this->dispatch('error', "No se pudo completar la accion, intente mas tarde.");
        }
    }
}