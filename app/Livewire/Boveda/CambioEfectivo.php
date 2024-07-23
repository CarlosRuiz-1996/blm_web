<?php

namespace App\Livewire\Boveda;

use Livewire\Component;
use App\Livewire\Forms\BovedaForm;
use App\Models\ctgDenominacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;

class CambioEfectivo extends Component
{
    use WithPagination;
    public BovedaForm $form;
    public $readyToLoad = false;
    public function loadServicios()
    {
        $this->readyToLoad = true;
    }
    public function render()
    {
        if ($this->readyToLoad) {
            $denominaciones = $this->form->getDenominaciones();
            $cambios = $this->form->getCambios();
        } else {
            $cambios = [];
            $denominaciones = [];
        }
        return view('livewire.boveda.cambio-efectivo', compact('denominaciones', 'cambios'));
    }

    public function updating($property, $value)
    {
        if (str_contains($property, 'monto')) {
            // Obtén el índice del campo que se actualizó
            $index = str_replace(['cambios.', '.monto'], '', $property);

            // dd($property.'-'.$index);
            foreach ($this->form->cambios as $i => $cambio) {
                // $monto_suma += $cambio['monto'];
                if ($i == $index) {
                    $denominacion = ctgDenominacion::find($cambio['denominacion']);

                    if ($denominacion->cantidad > $value) {
                        $this->addError($property, 'El monto debe de ser mayor a la denominacion elegida.');
                    } else {
                        $this->resetValidation($property);
                    }

                    if ($value % 200 != 0) {
                        $this->addError($property, 'Solo puedes ingresar un monto valido.');
                    } else {
                        $this->resetValidation($property);
                    }
                }
            }
        }

        if (str_contains($property, 'denominacion')) {
            $index = str_replace(['cambios.', '.denominacion'], '', $property);

            $this->reset('cambios.' . $index . '.monto');
        }
    }
    public function add()
    {
        $this->validate([
            'form.monto' => 'required|numeric|min:1',
        ], [
            'form.monto.required' => 'El monto es obligatorio',
            'form.monto.numeric' => 'El monto debe ser un número',
            'form.monto.min' => 'El monto no debe ser al menos 0',
        ]);
        $this->form->cambios[] = [
            'denominacion' => '',
            'monto' => '',

        ];
    }

    public function guardar()
    {
        $this->validate([
            'form.cambios.*.monto' => 'required|numeric|min:1',
            'form.monto' => 'required|numeric|min:1',
            'form.cambios.*.denominacion' => 'required',

        ], [
            'form.cambios.*.monto.required' => 'La cantidad es obligatoria',
            'form.cambios.*.monto.numeric' => 'La cantidad debe ser un número',
            'form.cambios.*.monto.min' => 'La cantidad no debe ser al menos 0',
            'form.monto.required' => 'El monto es obligatorio',
            'form.monto.numeric' => 'El monto debe ser un número',
            'form.monto.min' => 'El monto no debe ser al menos 0',
            'form.cambios.*.denominacion.required' => 'La denominacion es obligatoria',

        ]);
        try {
            DB::beginTransaction();
            //segunda validacion:
            $monto_suma = 0;
            foreach ($this->form->cambios as $index => $cambio) {
                $monto_suma += $cambio['monto'];
            }

            if ($monto_suma != $this->form->monto) {
                throw new \Exception('El cambio esta mal, verifica los montos');
            }

            $this->form->saveCambioEfectivo();

            $this->dispatch('alert', ['msg' => 'Registro guardado con exito'], ['tipomensaje' => 'success']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', ['msg' => $e->getMessage()], ['tipomensaje' => 'error']);

            // Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
            // Log::info('Info: ' . $e);
        }
    }

    public function clean()
    {
        $this->reset('monto', 'cambios');
    }
}
