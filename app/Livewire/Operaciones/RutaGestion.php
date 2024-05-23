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
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;


class RutaGestion extends Component
{

    public $op;
    public $rutas;
    public RutaForm $form;
    public $dia_select = false;
    public $total_ruta;
    public function mount($ruta = null, $op)
    {
        $this->op = $op;
        if ($ruta) {
            $ruta = Ruta::find($ruta);
            $this->form->ruta = $ruta;
            $this->form->hora_inicio = $ruta->hora_inicio;
            $this->form->hora_fin = $ruta->hora_fin;
            $this->form->ctg_rutas_id = $ruta->nombre->name;
            $this->form->ctg_ruta_dia_id = $ruta->dia->name;
        }
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
                $this->dispatch('success',  ['La ruta paso al proceso de gestión en boveda']);
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
        $users = Empleado::whereIn('ctg_area_id', [2, 3])->get();
        //genera el mensaje
        $msg = 'Ser requiere validacion para que la ruta ' . $this->form->ruta->nombre->name . ' lleve mas de 10 millones';


        //Insertar en notificaciones de boveda
        ModelsNotification::create([
            'user_id_send' => Auth::user()->id,
            'ctg_area_id' => 3,
            'message' => $msg
        ]);
        ModelsNotification::create([
            'user_id_send' => Auth::user()->id,
            'ctg_area_id' => 2,
            'message' => $msg
        ]);

        Notification::send($users, new \App\Notifications\newNotification($msg));
        
    }
}
