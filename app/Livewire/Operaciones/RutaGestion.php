<?php

namespace App\Livewire\Operaciones;

use Livewire\Component;
use App\Livewire\Forms\RutaForm;
use App\Models\Empleado;
use App\Models\Notification as ModelsNotification;
use App\Models\Ruta;
use App\Models\RutaEmpleados;
use App\Models\RutaFirma10M;
use App\Models\RutaServicio;
use App\Models\RutaVehiculo;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;


class RutaGestion extends Component
{

    public $op;
    public $rutas;
    public RutaForm $form;
    public $dia_select = false;
    public $total_ruta;
    public $originalHoraInicio;
    public $originalHoraFin;
    public $originalCtgRutasId;
    public $originalCtgRutaDiaId;
    public $originalDiaselec;
    public $originaldia_id_calendario;
    public function mount($ruta = null, $op)
    {
        $this->op = $op;
        if ($ruta) {
            $ruta = Ruta::find($ruta);
            // Guardar los valores originales en propiedades temporales
            $this->originalHoraInicio = $ruta->hora_inicio;
            $this->originalHoraFin = $ruta->hora_fin;
            $this->originalCtgRutasId = $ruta->nombre->name;
            $this->originalCtgRutaDiaId = $ruta->dia->name;
            $this->originalDiaselec=$ruta->dia->id;
            $this->originaldia_id_calendario=$this->form->calcularProximoDia(now(),$ruta->dia->id)->toDateString();

            $this->form->ruta = $ruta;
            $this->form->hora_inicio = $ruta->hora_inicio;
            $this->form->hora_fin = $ruta->hora_fin;
            $this->form->ctg_rutas_id = $ruta->nombre->name;
            $this->form->ctg_ruta_dia_id = $ruta->dia->name;
            $this->form->botonhablitarruta=false;
            $this->form->diaid=$ruta->dia->id;
            $this->form->dia_id_calendario=$this->form->calcularProximoDia(now(),$ruta->dia->id)->toDateString();
        }
    }



    public function guardaredicionhoraruta()
    {
        // Formatear horas a HH:MM
        $this->form->hora_inicio = date('H:i', strtotime($this->form->hora_inicio));
        $this->form->hora_fin = date('H:i', strtotime($this->form->hora_fin));
    
        // Validación
        $this->validate([
            'form.hora_inicio' => 'required|date_format:H:i|before:form.hora_fin',
            'form.hora_fin' => 'required|date_format:H:i|after:form.hora_inicio',
        ], [
            'form.hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'form.hora_inicio.date_format' => 'La hora de inicio debe tener el formato HH:MM.',
            'form.hora_inicio.before' => 'La hora de inicio debe ser anterior a la hora de finalización.',
            'form.hora_fin.required' => 'La hora de fin es obligatoria.',
            'form.hora_fin.date_format' => 'La hora de fin debe tener el formato HH:MM.',
            'form.hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
        ]);
    
        DB::transaction(function () {
            $ruta = Ruta::find($this->form->ruta->id); // Asegúrate de que `$this->form->ruta` esté correctamente asignada.
    
            if ($ruta) {
                // Actualizar los valores de hora_inicio y hora_fin
                $ruta->hora_inicio = $this->form->hora_inicio;
                $ruta->hora_fin = $this->form->hora_fin;
    
                $mensaje = 'La ruta se editó con éxito'; // Mensaje base
    
                // Verificar si el dia_id_calendario ha cambiado
                if ($this->form->dia_id_calendario !== $this->originaldia_id_calendario) {
                    // Actualizar los servicios asociados a la ruta con estatus 1
                    RutaServicio::where('ruta_id', $ruta->id)
                    ->where('status_ruta_servicios', 1)
                    ->update(['fecha_servicio' => $this->form->dia_id_calendario]);
    
                    // Concatenar el mensaje sobre la actualización de los servicios
                    $mensaje .= ', y se actualizaron los registros que ya existían para esta ruta con el día seleccionado.';
                }
    
                // Guardar los cambios en la base de datos para la ruta
                $ruta->save();
                $this->form->botonhablitarruta = false;
    
                // Emitir el mensaje completo
                $this->dispatch('successRutaHora', [$mensaje]);
            } else {
                throw new \Exception('Ruta no encontrada');
            }
        });
    }
    


    
    public function editarrutahora(){
        $this->resetErrorBag(); // Resetea todos los errores de validación
        $this->resetValidation();
        $this->form->botonhablitarruta=true; 
    }
    public function cancelaredicionhoraruta()
    {
        // Restablecer los valores originales
        $this->resetErrorBag(); // Resetea todos los errores de validación
        $this->resetValidation();
        $this->form->hora_inicio = $this->originalHoraInicio;
        $this->form->hora_fin = $this->originalHoraFin;
        $this->form->ctg_rutas_id = $this->originalCtgRutasId;
        $this->form->ctg_ruta_dia_id = $this->originalCtgRutaDiaId;
        $this->form->dia_id_calendario=$this->originaldia_id_calendario;
        // Deshabilitar el modo de edición
        $this->form->botonhablitarruta = false;
    }

    // public $dia;
    #[On('render-rutas')]
    public function render()
    {
        $dias = $this->form->getCtgDias();
        $boveda_pase = 0;
        if ($this->form->ruta) {
            $vehiculos = RutaVehiculo::where('ruta_id', $this->form->ruta->id)->count();
            $personal = RutaEmpleados::where('ruta_id', $this->form->ruta->id)
                ->whereHas('empleado', function ($query) {
                    $query->where('ctg_area_id', 16);
                })
                ->count();
            $operador = RutaEmpleados::where('ruta_id', $this->form->ruta->id)
                ->whereHas(
                    'empleado',
                    function ($query) {
                        $query->where('ctg_area_id', 18);
                    }
                )
                ->count();
            $servicios = RutaServicio::where('ruta_id', $this->form->ruta->id)->count();

            if ($vehiculos > 0 && $personal > 0 && $operador > 0 && $servicios > 0) {
                $boveda_pase = 1;
            }

            $this->total();
        }



        return view('livewire.operaciones.ruta-gestion', compact('dias', 'boveda_pase'));
    }

    #[On('total-ruta')]
    public function total()
    {
        $this->total_ruta = RutaServicio::where('ruta_id', $this->form->ruta->id)->sum('monto');
        if ($this->total_ruta > 10000000) {
            $this->validar10m();
        }
    }
    public function updated($property)
    {
        if ($property === 'form.ctg_ruta_dia_id') {
            $this->reset('form.hora_inicio', 'form.hora_fin', 'form.ctg_rutas_id');
            if ($this->form->ctg_ruta_dia_id != 0) {
                $this->dia_select = true;
                $this->rutas = $this->form->getCtgRutas($this->form->ctg_ruta_dia_id);
            } else {
                $this->dia_select = false;
            }
        }
    }
    #[On('save-ruta')]
    public function save()
    {

        // dd('save');
        $this->validate([
            'form.ctg_rutas_id' => 'required',
            'form.hora_inicio' => 'required',
            'form.ctg_ruta_dia_id' => 'required'
        ], [
            'form.ctg_rutas_id' => 'El nombre de la ruta es obligatorio',
            'form.hora_inicio' => 'La hora de inicio es obligatorio',
            'form.ctg_ruta_dia_id' => 'El dia de la ruta es obligatorio'
        ]);
        // $this->form->ruta = 1;
        $res =  $this->form->store();
        if ($res == 1) {
            $this->dispatch('success', ['La ruta se creo con exito', 'Ahora vamos complementarla', $this->form->ruta->id]);
        } else {
            $this->dispatch('error', 'Ocurrio un error, Intenta mas tarde');
        }
    }

    #[On('update-ruta')]
    public function update($accion)
    {
        if ($accion == 1) {

            $res = $this->form->boveda();

            if ($res == 1) {
                $this->dispatch('success',  ['La ruta paso al proceso de gestión en boveda', '',$this->form->ruta->id]);
            } else {
                $this->dispatch('error', 'Hubo un error, intenta mas tarde');
            }
        }
    }

    public $firma;

    public function validar10m()
    {
        $this->firma = $this->form->validafirma10m();

    }

    #[On('insert-firmas')]
    public function insertFirma10m()
    {
        $this->form->insertfirma10m();
        //obtener los usuarios con el area de boveda y operaciones.
      
    }
}
