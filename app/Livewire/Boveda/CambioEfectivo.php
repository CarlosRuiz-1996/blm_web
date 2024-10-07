<?php

namespace App\Livewire\Boveda;

use Livewire\Component;
use App\Livewire\Forms\BovedaForm;
use App\Models\CambioEfectivo as ModelsCambioEfectivo;
use App\Models\CambioEfectivoDenominaciones;
use App\Models\ctgDenominacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;

class CambioEfectivo extends Component
{
    use WithPagination;
    public BovedaForm $form;
    public $cantidadTotal;
    public $tipoCambio;
    public $tipoCambioMonedas;
    public $denominacionesPermitidas = [];
    public $cambioBolsas = [];
    public $sumaTotal = 0;
    public $sumaTotalbolsas = 0;
    public $sumaIncorrecta = false;
    public $monedasDisponibles;

    public $billetesDisponibles;
    public $bolsasDisponibles;
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

    
    public function mount()
    {
        // Cargar denominaciones de billetes y monedas al iniciar el componente
        $this->billetesDisponibles = ctgDenominacion::where('ctg_tipo_moneda_id', '3')->get();
        $this->monedasDisponibles = ctgDenominacion::where('ctg_tipo_moneda_id', '2')->get();
        $this->bolsasDisponibles = ctgDenominacion::where('ctg_tipo_moneda_id', '2')->get();
    }
    public function updated($propertyName)
{
    if ($propertyName === 'tipoCambio') {
        // Reiniciar los valores cuando se cambie el tipo de cambio
        $this->reiniciarValores();
    }

    // Reinicia la suma total y la suma incorrecta
    $this->sumaTotal = 0;
    $this->sumaIncorrecta = false;

    // Calcula la suma total según el tipo de cambio seleccionado
    if ($this->tipoCambio) {
        if ($this->tipoCambio == 'billete_a_moneda' || $this->tipoCambio == 'moneda_a_menor_denominacion') {
            $this->calcularSumaMonedas();
            $this->sumaIncorrecta = ((int)$this->sumaTotal !== (int)$this->cantidadTotal);
        } elseif ($this->tipoCambio == 'moneda_a_billete' || $this->tipoCambio == 'billete_a_menor_denominacion') {
            $this->calcularSumaBilletes();
            $this->sumaIncorrecta = ((int)$this->sumaTotal !== (int)$this->cantidadTotal);
        } elseif ($this->tipoCambio == 'moneda_a_bolsas') {
            $this->calcularSumaBolsas();
            $this->calcularSumaBilletes();
            $this->sumaIncorrecta = ((int)$this->sumaTotal !== (int)$this->sumaTotalbolsas);
        }
    }
}
        private function reiniciarValores()
        {
            $this->denominacionesPermitidas = [];
            $this->cambioBolsas = [];
            $this->sumaTotal = 0;
            $this->sumaTotalbolsas = 0;
            $this->sumaIncorrecta = false;
        }
        public function clean(){
            $this->form->from_change='';
            $this->cantidadTotal=0;
            $this->tipoCambio='';
            $this->tipoCambioMonedas='';
            $this->denominacionesPermitidas = [];
            $this->cambioBolsas = [];
            $this->sumaTotal = 0;
            $this->sumaTotalbolsas = 0;
            $this->sumaIncorrecta = false;
        }


    private function calcularSumaMonedas()
    {
        $this->sumaTotal=0;
        foreach ($this->monedasDisponibles as $moneda) {
            $cantidad = $this->denominacionesPermitidas[$moneda->id] ?? 0;
            $this->sumaTotal += (int)$cantidad * (float)$moneda->cantidad;
        }
    }

    private function calcularSumaBilletes()
    {
        $this->sumaTotal=0;
        foreach ($this->billetesDisponibles as $billete) {
            $cantidad = $this->denominacionesPermitidas[$billete->id] ?? 0;
            $this->sumaTotal += (int)$cantidad * (float)$billete->cantidad;
        }
    }

    private function calcularSumaBolsas()
    {
        $this->sumaTotalbolsas=0;
        foreach ($this->bolsasDisponibles as $bolsa) {
            $cantidad = $this->cambioBolsas[$bolsa->id] ?? 0;
            $this->sumaTotalbolsas += (int)$cantidad *  ((float)$bolsa->piezas * (float)$bolsa->cantidad);
        }
    } 

    public function guardar()
    {
        // Validar las entradas
        $this->validate([
            'cantidadTotal' => 'required|numeric|min:1',
            'denominacionesPermitidas.*' => 'required|numeric|min:0', // Asegurarse de que sean números y no negativos
            // Agrega otras validaciones que consideres necesarias
        ], [
            'cantidadTotal.required' => 'El monto total es obligatorio',
            'cantidadTotal.numeric' => 'El monto total debe ser un número',
            'cantidadTotal.min' => 'El monto total debe ser al menos 1',
            'denominacionesPermitidas.*.required' => 'Las denominaciones son obligatorias',
            'denominacionesPermitidas.*.numeric' => 'Las denominaciones deben ser números válidos',
            'denominacionesPermitidas.*.min' => 'Las denominaciones no deben ser negativas',
        ]);
    
        try {
            DB::beginTransaction();
    
            // Comprobar que la suma total sea correcta
            if ($this->sumaIncorrecta) {
                throw new \Exception('La suma total de las denominaciones no coincide con el monto total.');
            }
    
            // Guardar el cambio de efectivo
            $cambioEfectivo = ModelsCambioEfectivo::create([
                'monto' => $this->cantidadTotal,
                'empleado_boveda_id' => Auth::user()->empleado->id,
                'from_change' => $this->form->from_change
            ]);
    
            // Guardar las denominaciones permitidas
            foreach ($this->denominacionesPermitidas as $denominacionId => $cantidad) {
                if ($cantidad > 0) {
                    // Busca la denominación por su ID
                    $denominacion = ctgDenominacion::find($denominacionId);
                    
                    if ($denominacion) {
                        // Calcula el monto
                        $monto = $denominacion->cantidad * $cantidad; // Asegúrate de que 'cantidad' sea el campo correcto
            
                        CambioEfectivoDenominaciones::create([
                            'cambio_efectivo_id' => $cambioEfectivo->id,
                            'ctg_denominacion_id' => $denominacionId,
                            'monto' => $monto,
                        ]);
                    }
                }
            }            
            DB::commit();
            $this->dispatch('alert', ['msg' => 'Registro guardado con éxito'], ['tipomensaje' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', ['msg' => $e->getMessage()], ['tipomensaje' => 'error']);
        }
    }
    

}
