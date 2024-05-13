<?php

namespace App\Livewire\Operadores;

use App\Models\Empleado;
use App\Models\Ruta;
use App\Models\RutaEmpleados;
use App\Models\RutaServicio;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class OperadoresIndex extends Component
{
    public $tiposervicio;
    public $MontoEntregado;
    public $MontoEntrega;
    #[Validate(['photo.*' => 'image|max:1024'])]
    public $photo;
    protected $listeners = ['modalCerrado'];
    use WithFileUploads;
    public function render()
    {
        $AutorizadoOperador=null;
        $identificado=false;
        $rutaEmpleados=[];
        $nombreUsuario ="";
        $UsuarioLogin = Auth::user();
        if($UsuarioLogin){
            $nombreUsuario =$UsuarioLogin->name.' '.$UsuarioLogin->paterno.' '.$UsuarioLogin->materno;
            $AutorizadoOperador=Empleado::where('user_id',$UsuarioLogin->id)->where('ctg_area_id',18)->first();
        }
        if($AutorizadoOperador){
            $rutaEmpleados= Ruta::join('ruta_empleados', 'rutas.id', '=', 'ruta_empleados.ruta_id')
            ->where('rutas.ctg_rutas_estado_id', 3)
            ->where('ruta_empleados.status_ruta_empleados', 1)
            ->where('ruta_empleados.empleado_id', $AutorizadoOperador->id)
            ->get(['rutas.*']);
            $identificado=true;
        }else{
            $identificado=false;
        }

                                      
        // Pasa los resultados a la vista
        return view('livewire.operadores.operadores-index',compact('rutaEmpleados','nombreUsuario','identificado'));
    }
    public function ModalEntregaRecolecta($id, $tiposervicio) {
        $this->tiposervicio = $tiposervicio == 1 ? 'Entrega' : ($tiposervicio == 2 ? 'Recolección' : 'Otro');
        $servicioRuta=RutaServicio::find($id);
        $this->MontoEntrega=$servicioRuta->monto;
    }

    public function ModalAceptar(){
        $this->validate([
            'MontoEntrega' => 'required',
            'MontoEntregado' => 'required',
            'photo' => 'required',
        ]);
        if($this->MontoEntrega == $this->MontoEntregado){
            $this->photo->storeAs(path: 'evidencias/', name: 'avatar.png');
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La cantidad Ingresada es correcta'], ['tipomensaje' => 'success']);
        }else{
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La cantidad Ingresada no es la cantidad a entregar'], ['tipomensaje' => 'error']);
        }
    }
    public function openCamera()
    {
        $this->dispatch('openCamera');
    }


 
    public function updatedPhoto()
    {
        foreach ($this->photo as $photo) {
            $photo->store(path: 'photo');
        }
    }


    public function modalCerrado()
    {
        $this->MontoEntrega = null;
        $this->MontoEntregado = null;
        $this->photo = null;
    }
    public function ModalAceptarRecolecta(){
        $this->validate([
            'MontoEntregado' => 'required',
            'photo' => 'required',
        ]);
            $this->photo->storeAs(path: 'evidencias/', name: 'avatar.png');
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La cantidad Ingresada es correcta'], ['tipomensaje' => 'success']);
    }

    public function empezarRuta($id){
       $ruta=Ruta::find($id);
       $ruta->status_ruta=2;
       $ruta->save();
       $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La ruta a iniciado'], ['tipomensaje' => 'success']);
    }
    public function TerminarRuta($id){
        $ruta=Ruta::find($id);
        $ruta->status_ruta=3;
        $ruta->ctg_rutas_estado_id=4;
        $ruta->save();
        $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La ruta a sido terminada'], ['tipomensaje' => 'success']);
     }

    
    
}
