<?php

namespace App\Livewire\Rh;

use App\Models\AsignacionHerramientaEmpleado;
use App\Models\HerramientaEmpleado;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Empleadosherramientas extends Component
{
    public $empleadoId;
    public $idempleado;
    public $herramientas;
    public $ctgherramienta;

    //incializa el compoonente
    public function mount(){
        $this->idempleado= $this->empleadoId;
        $this->herramientas=HerramientaEmpleado::all(); 
    }

    //renderiza el componente con las herramientas del empleado seleccionado
    public function render()
    {
        $empleadoHerramientas=AsignacionHerramientaEmpleado::where('empleado_id',$this->empleadoId)->get();
        return view('livewire.rh.empleadosherramientas',compact('empleadoHerramientas'));
    }


    //funcion cuando abre modal asigna el id del empleado y catalogo de herraminetas para asignar
    public function modalherramienta(){
     $this->idempleado= $this->empleadoId;
     $this->herramientas=HerramientaEmpleado::all();
    }

    //funcion para guardar informacion de las herramientas asignadas a cada empleado
    public function modalherramientaGuardar(){
        $this->validate([
            'idempleado' => 'required',
            'ctgherramienta' => 'required|string|max:255',
        ]);
        try {
            DB::transaction(function () {
                $existe = AsignacionHerramientaEmpleado::where('empleado_id', $this->idempleado)
                ->where('herramienta_id', $this->ctgherramienta)
                ->exists();
            if($existe){
                $this->dispatch('error', ['Ya cuenta con esta herramienta']);
            }else{
                AsignacionHerramientaEmpleado::create([
                    'empleado_id'  => $this->idempleado,
                    'herramienta_id'  => $this->ctgherramienta,
                    'status_asignacion_herramienta'=> 1,
                    'fecha_entrega'=> Carbon::now(),
                    'fecha_cambio'=> null,
                    'fecha_devolucion'=> null,
                ]);
                $this->dispatch('success', ['La herramienta se asigno con éxito']);
            }     
    });      
        
    } catch (\Exception $e) {
        dd($e->getMessage()); 
        $this->dispatch('error', ['Ocurrió un error al registrar la herramienta']);
    }
       }
        //estatus 1 estatus activo
        //estatus 2 estatus dado de baja
        //estatus 3 estatus cambiado

        //funcion para asignar si se cambio el equipo del empleado
       public function cambiar($idherramienta){
        $herramienta=AsignacionHerramientaEmpleado::find($idherramienta);
        $herramienta->fecha_cambio=Carbon::now();
        $herramienta->fecha_devolucion=Carbon::now();
        $herramienta->status_asignacion_herramienta=3;
        $herramienta->save();
        $this->dispatch('success', ['La herramienta se cambio']);
       }
       //funcion para asignar si se dio de baja el equipo del empleado
       public function darDeBaja($idherramienta){
        $herramienta=AsignacionHerramientaEmpleado::find($idherramienta);
        $herramienta->fecha_devolucion=Carbon::now();
        $herramienta->status_asignacion_herramienta=2;
        $herramienta->save();
        $this->dispatch('success', ['La herramienta se dio de baja para este empleado']);
       }
              //funcion para eliminar el equipo del empleado
              //borra registro  relacionado solo con el empleado 
       public function eliminar($idherramienta){
        $herramienta=AsignacionHerramientaEmpleado::find($idherramienta);
        $herramienta->delete();
        $this->dispatch('success', ['La herramienta se elimino para este empleado']);
        
       }
}
