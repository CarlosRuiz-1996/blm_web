<?php

namespace App\Livewire\Notifications;

use App\Livewire\Forms\NotificationsForm;
use App\Models\ClienteMontos;
use App\Models\Empleado;
use App\Models\MontoBlm;
use App\Models\Notification;
use App\Models\RutaFirma10M;
use Exception;
use Illuminate\Support\Facades\Notification as NotificationsNotification;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;


class NotificationNavBar extends Component
{

    public NotificationsForm $form;
    public $notificationes;
    public $totalNotifications;
    public $notificacionaelimi;
    public function getListeners()
    {
        $empleado_id = Auth::user()->empleado->id;
        return [
            // Private Channel
            // "echo:notification.{$empleado_id},notification" => 'render'
            "echo-notification:App.Models.Empleado.{$empleado_id},notification" => 'render',
        ];
    }


    public function render()
    {
        $this->notificationes = Notification::where('ctg_area_id', Auth::user()->empleado->ctg_area_id)
            ->where('status_notificacion', 1)
            ->latest()
            ->take(5)
            ->get();

        // Obtener el conteo total de notificaciones para el área específica
        $this->totalNotifications = Notification::where('ctg_area_id', Auth::user()->empleado->ctg_area_id)
            ->where('status_notificacion', 1)
            ->count();
        // dd($notification);
        return view('livewire.notifications.notification-nav-bar');
    }


    #[On('validar-10m')]
    public function validation($respuesta, $notification)
    {
        $notificacion = Notification::find($notification);

        if (!$notificacion) {
        } else {

            try {
                DB::beginTransaction();

                $usuario = Auth::user();
                $empleado = $usuario->empleado ?? null;

                if (!$empleado) {
                    throw new \Exception('Empleado no encontrado para el usuario.');
                }

                $area_id = $empleado->ctg_area_id;
                $firma = RutaFirma10M::find($notificacion->ruta_firma_id);

                if (!$firma) {
                    throw new \Exception('Firma no encontrada.');
                }

                $empleado_id = $empleado->id;
                switch ($area_id) {
                    case 2:
                        $firma->empleado_id_operaciones = $empleado_id;
                        $firma->confirm_operaciones = $respuesta;
                        break;
                    case 3:
                        $firma->empleado_id_boveda = $empleado_id;
                        $firma->confirm_boveda = $respuesta;
                        break;
                    case 9:
                        $firma->empleado_id_direccion = $empleado_id;
                        $firma->confirm_direccion = $respuesta;
                        break;
                    default:
                        throw new \Exception('ID de área no válido.');
                }

                $firma->save();
                if (($firma->confirm_boveda === 0) || ($firma->confirm_operaciones === 0)) {

                    //revisa si ya se envio una notificacion a direccion...
                    $existe_notif = Notification::where('ruta_firma_id', $firma->id)->where('ctg_area_id', 9)->first();

                    if (!$existe_notif) {
                        //genera el mensaje
                        $msg = $notificacion->message;
                        Notification::create([
                            'empleado_id_send' => Auth::user()->empleado->id,
                            'ctg_area_id' => 9,
                            'message' => $msg,
                            'ruta_firma_id' => $firma->id,
                            'tipo' => 2
                        ]);
                        $users = Empleado::whereIn('ctg_area_id', [9])->get();
                        NotificationsNotification::send($users, new \App\Notifications\newNotification($msg));
                    }

                    //envia la notififcacion a operaciones y direccion:
                    if ($firma->confirm_direccion === 1) {
                        //genera el mensaje
                        $this->notificaciones($notificacion, $firma);
                    } else if ($firma->confirm_direccion === 0) {
                        $msg = $notificacion->message . "..Rechazada.";
                        Notification::create([
                            'empleado_id_send' => Auth::user()->empleado->id,
                            'ctg_area_id' => 2,
                            'message' => $msg,
                            'ruta_firma_id' => $firma->id,
                            'tipo' => 1
                        ]);
                        $users = Empleado::whereIn('ctg_area_id', [2])->get();
                        NotificationsNotification::send($users, new \App\Notifications\newNotification($msg));
                    }
                } else if (($firma->confirm_boveda == 1 && $firma->confirm_operaciones == 1) ||  $firma->confirm_direccion === 1) {
                    //genera el mensaje

                    $this->notificaciones($notificacion, $firma);
                }



                $notificacion->delete(); // Eliminar la notificación después de actualizar la firma



                DB::commit();
                $this->dispatch('success-firma10', ['Se notificara la decision a el area correspondiente', 'success']);
            } catch (\Exception $e) {
                DB::rollBack();
                $this->dispatch('success-firma10', ['Ha ocurrido un error, intenta más tarde' . $e, 'error']);
            }
        }
    }

    function notificaciones($notificacion, $firma)
    {
        //genera el mensaje
        $msg = $notificacion->message . "..Aceptada.";
        Notification::create([
            'empleado_id_send' => Auth::user()->empleado->id,
            'ctg_area_id' => 2,
            'message' => $msg,
            'ruta_firma_id' => $firma->id,
            'tipo' => 1
        ]);
        $users = Empleado::whereIn('ctg_area_id', [2])->get();
        NotificationsNotification::send($users, new \App\Notifications\newNotification($msg));

        //avisar a direccion que saldra la ruta.
        $msg = "La ruta" . $notificacion->firma->ruta->nombre->name . " saldra con mas de 10 millones.";
        Notification::create([
            'empleado_id_send' => Auth::user()->empleado->id,
            'ctg_area_id' => 9,
            'message' => $msg,
            'ruta_firma_id' => $firma->id,
            'tipo' => 1
        ]);
        $users = Empleado::whereIn('ctg_area_id', [9])->get();
        NotificationsNotification::send($users, new \App\Notifications\newNotification($msg));
    }

    public function deleteNotification($id)
    {
        $notificacion = Notification::find($id);
        $notificacion->delete();
    }

    //validar monto de bancos a clientes:
    #[On('banco-validar')]
    public function validarBanco($respuesta, $notification, $password)
    {
        // dd($respuesta.'-'. $notification.'-'.$password);

        $user = Auth::user();

        try {

            DB::beginTransaction();
            // Verificar si el password ingresado coincide con el password del usuario logueado
            if (Hash::check($password, $user->password)) {
                // Contraseña válida
                $ntf = Notification::find($notification);

                if ($ntf) {

                    $cliente_monto = ClienteMontos::find($ntf->ruta_firma_id);
                    $cliente_monto->status_cliente_monto = 2;
                    $cliente_monto->save();

                    $cliente_monto->cliente->resguardo = $cliente_monto->monto_new;
                    $cliente_monto->cliente->save();


                    MontoBlm::find(1)->decrement('monto', $cliente_monto->monto_in);

                    //notifica a bancos que ya se acepto el monto para el clientre
                    $rz = $cliente_monto->cliente->razon_social;
                    $mnt = number_format($cliente_monto->monto_in, 2, '.', ',');
                    $msg = "Se ha aprovado la cantidad de $mnt para el cliente $rz ";

                    //notifica a direccion 
                    Notification::create([
                        'empleado_id_send' => Auth::user()->empleado->id,
                        'ctg_area_id' => 19,
                        'message' => $msg,
                        'tipo' => 1,
                    ]);

                    $users = Empleado::whereIn('ctg_area_id', [19])->get();
                    NotificationsNotification::send($users, new \App\Notifications\newNotification($msg));

                    $this->deleteNotification($ntf->id);
                }
                $this->dispatch('alerta', ['success', 'Aprovación correcta.']);
            } else {

                $this->dispatch('alerta', ['error', 'Contraseña incorrecta']);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('alerta', ['error', 'Ha ocurrido un problema intenta más tarde']);
        }
    }
}
