<?php

namespace App\Livewire\Notifications;

use App\Models\Notification;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
class NotificationNavBar extends Component
{
    public $notifications;
 
    public function mount()
    {
        $this->notifications = [];
        
    }

    public function getListeners()
    {

        $empleado_id= Auth::user()->empleado->id;
        return [
            // Private Channel
            // "echo:notification.{$empleado_id},notification" => 'render'
            "echo-private: private-App.Models.Empleado.11,Illuminate\Notifications\Events\BroadcastNotificationCreated" => 'notifyNewOrder',   
             ];
    }
    public $showNewOrderNotification = false;

 
    public function render()
    {
        // dd(Auth::user()->empleado->ctg_area_id);
        $notificationes = Notification::where('ctg_area_id',  Auth::user()->empleado->ctg_area_id)->where('status_notificacion',1)->get();
        // dd($notification);
        return view('livewire.notifications.notification-nav-bar',compact('notificationes'));
    }

    public function notifyNewOrder()
    {
        $this->showNewOrderNotification = true;
    }
}
