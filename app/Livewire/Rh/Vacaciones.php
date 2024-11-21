<?php

namespace App\Livewire\Rh;

use App\Models\SolicitudVacacion;
use Livewire\Component;

class Vacaciones extends Component
{

    public $solicitudespen;
    public $solicitudesacep;
    public $solicitudesactivas;
    public $solicitudesactivascalen;
    public $solicitudestermin;

    //incializa el componente
    public function mount() {
        // 1. Actualizar el estatus de las solicitudes que ya han finalizado
        SolicitudVacacion::where('status_vacaciones', 1)
            ->where('fecha_fin', '<', now()) // fecha_fin menor a la fecha actual
            ->update(['status_vacaciones' => 4]); // Cambiar el estatus a 4 (finalizada o terminada)

            $this->solicitudesactivascalen = SolicitudVacacion::with(['empleado.user'])
            ->where('status_vacaciones', 1) // Vacaciones aprobadas
            ->get() // Obtener todas las solicitudes sin filtrar por fecha
            ->map(function ($solicitud) {
                return [
                    'empleado_id' => $solicitud->empleado_id,
                    'nombre_completo' => $solicitud->empleado->user->name . ' ' . $solicitud->empleado->user->paterno . ' ' . $solicitud->empleado->user->materno,
                    'fecha_inicio' => $solicitud->fecha_inicio,
                    'fecha_fin' => $solicitud->fecha_fin,
                ];
            })
            ->toArray(); // Convertir a array
    }
    //renderiza los tipo de solicituds de vacacion dependiendo el estaus
    public function render()
    {
        $this->solicitudespen=SolicitudVacacion::where('status_vacaciones',3)->count();
        $this->solicitudesacep=SolicitudVacacion::where('status_vacaciones',1)->count();
        $this->solicitudestermin = SolicitudVacacion::where('status_vacaciones',4)->count();
        $this->solicitudesactivas = SolicitudVacacion::where('fecha_inicio', '<=', now())
        ->where('fecha_fin', '>=', now())
        ->where('status_vacaciones',1)
        ->count();
        $this->solicitudesactivascalen = SolicitudVacacion::with(['empleado.user'])
        ->where('status_vacaciones', 1) // Vacaciones aprobadas
        ->get() // Obtener todas las solicitudes sin filtrar por fecha
        ->map(function ($solicitud) {
            return [
                'empleado_id' => $solicitud->empleado_id,
                'nombre_completo' => $solicitud->empleado->user->name . ' ' . $solicitud->empleado->user->paterno . ' ' . $solicitud->empleado->user->materno,
                'fecha_inicio' => $solicitud->fecha_inicio,
                'fecha_fin' => $solicitud->fecha_fin,
            ];
        })
        ->toArray(); // Convertir a array
        return view('livewire.rh.vacaciones');
    }
}
