<?php

namespace App\Livewire\Notifications;

use App\Livewire\Forms\NotificationsForm;
use App\Models\Notification;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;


class NotificationNavBar extends Component
{
    public $notifications;
    public NotificationsForm $form;

    public function mount()
    {
        $this->notifications = [];
    }

    public function getListeners()
    {

        $empleado_id = Auth::user()->empleado->id;
        return [
            // Private Channel
            // "echo:notification.{$empleado_id},notification" => 'render'
            "echo-notification:App.Models.Empleado.{$empleado_id},notification" => 'render',
        ];
    }
    public $showNewOrderNotification = false;


    public function render()
    {
        // dd(Auth::user()->empleado->ctg_area_id);
        $notificationes = Notification::where('ctg_area_id', Auth::user()->empleado->ctg_area_id)
            ->where('status_notificacion', 1)
            ->latest()
            ->take(5)
            ->get();

        // Obtener el conteo total de notificaciones para el área específica
        $totalNotifications = Notification::where('ctg_area_id', Auth::user()->empleado->ctg_area_id)
            ->where('status_notificacion', 1)
            ->count();

        // dd($notification);
        return view('livewire.notifications.notification-nav-bar', compact('notificationes', 'totalNotifications'));
    }


    #[On('validar-10m')]
    public function validation($respuesta, Notification $notification)
    {

        $this->form->notification = $notification;

        $res = $this->form->validar10m($respuesta);


        if ($res == 1) {
            // $notification->delete();
            // $this->reset('form.notification');

            $this->dispatch('success-firma10', ['La validacion se hizo correctamente', 'success']);
        } else {
            $this->dispatch('success-firma10', ['Ha ocurrido un error, intenta más tarde', 'error']);
        }

        $this->render();
    }
}
