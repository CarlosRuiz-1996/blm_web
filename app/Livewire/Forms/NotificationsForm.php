<?php

namespace App\Livewire\Forms;

use App\Models\Empleado;
// use App\Models\Notification;
use App\Models\Notification as ModelsNotification;
use Illuminate\Support\Facades\Notification;

use App\Models\RutaFirma10M;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;

class NotificationsForm extends Form
{
    //

    public $notification; // notificacion objet




    public function viewNotificacion()
    {
        $this->notification->status_notificacion = 2;
        $this->notification->save();
    }




    public function validar10m($respuesta)
    {
        try {
            DB::beginTransaction();

            $usuario = Auth::user();
            $empleado = $usuario->empleado ?? null;

            if (!$empleado) {
                throw new \Exception('Empleado no encontrado para el usuario.');
            }

            $area_id = $empleado->ctg_area_id;
            $firma = RutaFirma10M::find($this->notification->ruta_firma_id);

            if (!$firma) {
                throw new \Exception('Firma no encontrada.');
            }

            $empleado_id = $empleado->id;
            $notif = ModelsNotification::find($this->notification->id);

            if ($notif) {
                $notif->delete();
            } else {
                throw new \Exception('Notificación no encontrada.');
            }

            switch ($area_id) {
                case 2:
                    // operaciones
                    $firma->empleado_id_operaciones = $empleado_id;
                    $firma->confirm_operaciones = $respuesta;
                    break;
                case 3:
                    // bóveda
                    $firma->empleado_id_boveda = $empleado_id;
                    $firma->confirm_boveda = $respuesta;
                    break;
                case 8:
                    // dirección
                    $firma->empleado_id_direccion = $empleado_id;
                    $firma->confirm_direccion = $respuesta;
                    break;
                default:
                    throw new \Exception('ID de área no válido.');
            }

            $firma->save();

            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('No se pudo completar la solicitud: ' . $e->getMessage());
            Log::info('Traza de la excepción: ', ['exception' => $e]);
            return 0;
        }
    }
}
