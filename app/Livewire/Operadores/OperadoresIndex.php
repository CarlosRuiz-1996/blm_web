<?php

namespace App\Livewire\Operadores;

use App\Models\Empleado;
use App\Models\Ruta;
use App\Models\RutaEmpleadoReporte;
use App\Models\RutaEmpleados;
use App\Models\RutaServicio;
use App\Models\RutaServicioReporte;
use App\Models\RutaVehiculo;
use App\Models\RutaVehiculoReporte;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class OperadoresIndex extends Component
{
    public $idrecolecta;
    public $tiposervicio;
    public $MontoEntregado;
    public $MontoEntrega;
    public $envasescantidad;
    public $IdservicioReprogramar;
    public $motivoReprogramarConcepto;
    #[Validate(['photo.*' => 'image|max:1024'])]
    public $photo;
    #[Validate(['photo.*' => 'image|max:1024'])]
    public $photorepro;
    protected $listeners = ['modalCerrado', 'modalCerradoReprogramar'];

    use WithFileUploads;
    public function render()
    {
        $AutorizadoOperador = null;
        $identificado = false;
        $rutaEmpleados = [];
        $nombreUsuario = "";
        $UsuarioLogin = Auth::user();
        if ($UsuarioLogin) {
            $nombreUsuario = $UsuarioLogin->name . ' ' . $UsuarioLogin->paterno . ' ' . $UsuarioLogin->materno;
            $AutorizadoOperador = Empleado::where('user_id', $UsuarioLogin->id)->where('ctg_area_id', 18)->first();
        }
        if ($AutorizadoOperador) {
            $rutaEmpleados = Ruta::join('ruta_empleados', 'rutas.id', '=', 'ruta_empleados.ruta_id')
                ->where('rutas.ctg_rutas_estado_id', 3)
                ->where('ruta_empleados.status_ruta_empleados', 1)
                ->where('ruta_empleados.empleado_id', $AutorizadoOperador->id)
                ->get(['rutas.*']);
            $identificado = true;
        } else {
            $identificado = false;
        }


        // Pasa los resultados a la vista
        return view('livewire.operadores.operadores-index', compact('rutaEmpleados', 'nombreUsuario', 'identificado'));
    }
    public function ModalEntregaRecolecta($id, $tiposervicio)
    {
        $this->tiposervicio = $tiposervicio == 1 ? 'Entrega' : ($tiposervicio == 2 ? 'Recolección' : 'Otro');
        $servicioRuta = RutaServicio::find($id);
        $this->MontoEntrega = $servicioRuta->monto;
        $this->idrecolecta = $id;
    }

    public function ModalAceptar()
    {
        $this->validate([
            'idrecolecta' => 'required',
            'MontoEntrega' => 'required',
            'MontoEntregado' => 'required',
            'photo' => 'required',
        ]);
        if ($this->MontoEntrega == $this->MontoEntregado) {
            $servicioRuta = RutaServicio::find($this->idrecolecta);
            $servicioRuta->status_ruta_servicios = 3;
            $servicioRuta->save();
            $rutaServicioReporte = new RutaServicioReporte();

            // Asignar valores del servicio actualizado al reporte
            $rutaServicioReporte->servicio_id = $servicioRuta->servicio_id;
            $rutaServicioReporte->ruta_id = $servicioRuta->ruta_id;
            $rutaServicioReporte->monto = $servicioRuta->monto;
            $rutaServicioReporte->folio = $servicioRuta->folio;
            $rutaServicioReporte->envases = $servicioRuta->envases;
            $rutaServicioReporte->tipo_servicio = $servicioRuta->tipo_servicio;
            $rutaServicioReporte->status_ruta_servicio_reportes = $servicioRuta->status_ruta_servicios;
            $rutaServicioReporte->motivocancelacion = $this->motivoReprogramarConcepto;
            $rutaServicioReporte->area = 3;
            // Guardar el nuevo registro en la base de datos
            $rutaServicioReporte->save();
            $this->photo->storeAs(path: 'evidencias/', name: 'avatar.png');
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La cantidad Ingresada es correcta'], ['tipomensaje' => 'success']);
        } else {
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La cantidad Ingresada no es la cantidad a entregar'], ['tipomensaje' => 'error']);
        }
    }
    public function updatedPhoto()
    {
        foreach ($this->photo as $photo) {
            $photo->store(path: 'photo');
        }
    }
    public function updatedPhotorepro()
    {
        foreach ($this->photorepro as $photorepro) {
            $photorepro->store(path: 'photorepro');
        }
    }


    public function modalCerrado()
    {
        $this->MontoEntrega = null;
        $this->MontoEntregado = null;
        $this->photo = null;
    }
    public function modalCerradoReprogramar()
    {
        $this->IdservicioReprogramar = null;
        $this->motivoReprogramarConcepto = null;
        $this->photorepro = null;
    }

    public function ModalAceptarRecolecta()
    {
        $this->validate([
            'idrecolecta' => 'required',
            'MontoEntregado' => 'required',
            'envasescantidad' => 'required',
            'photo' => 'required',
        ]);

        $servicioruta=RutaServicio::find($this->idrecolecta);
        $servicioruta->monto=$this->MontoEntregado;
        $servicioruta->envases=$this->envasescantidad;
        $servicioruta->status_ruta_servicios=3;
        $servicioruta->save();

        $ruta=Ruta::find($servicioruta->ruta_id);
        $ruta->total_ruta=$ruta->total_ruta+$this->MontoEntregado;
        $ruta->save();
        $nombreRutaGuardaImg='Servicio_'.$servicioruta->id.'_evidencia.png';
        $this->photo->storeAs(path: 'evidencias/EntregasRecolectas/', name: $nombreRutaGuardaImg);
        $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La recolecta se completo correctamente'], ['tipomensaje' => 'success']);
    }

    public function empezarRuta($id)
    {
        $ruta = Ruta::find($id);
        $ruta->status_ruta = 2;
        $ruta->save();
        $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La ruta a iniciado'], ['tipomensaje' => 'success']);
    }
    public function TerminarRuta($id)
    {
        $serviciosPendientes = RutaServicio::where('ruta_id', $id)
            ->where('status_ruta_servicios', 2)
            ->count();

        if ($serviciosPendientes > 0) {
            // Si hay servicios pendientes con estado 2, envía un mensaje de error
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'No se puede terminar la ruta porque aún tiene servicios pendientes'], ['tipomensaje' => 'error']);
        } else {
            // Si no hay servicios pendientes con estado 2, actualiza el estado de la ruta
            $ruta = Ruta::find($id);
            $ruta->status_ruta = 3;
            $ruta->ctg_rutas_estado_id = 4;
            $ruta->save();
            $rutaempleados = RutaEmpleados::where('ruta_id', $id)->get();

            foreach ($rutaempleados as $rutaempleado) {
                $rutaempleado->status_ruta_empleados = 2;
                $rutaempleado->save();
            }
            foreach ($rutaempleados as $rutaempleado) {
                RutaEmpleadoReporte::create([
                    'ruta_id' => $rutaempleado->ruta_id,
                    'empleado_id' => $rutaempleado->empleado_id,
                    'status_ruta_empleado_reportes' => 2,
                ]);
            }
            $rutaVehiculos = RutaVehiculo::where('ruta_id', $id)->get();
            foreach ($rutaVehiculos as $rutaVehiculo) {
                $rutaVehiculo->status_ruta_vehiculos = 2;
                $rutaVehiculo->save();
            }
            foreach ($rutaVehiculos as $rutaVehiculo) {
                RutaVehiculoReporte::create([
                    'ruta_id' => $rutaVehiculo->ruta_id,
                    'ctg_vehiculo_id' => $rutaVehiculo->ctg_vehiculo_id,
                    'status_ruta_vehiculo_reportes' => 2,
                ]);
            }


            // Envía un mensaje de éxito
            $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'La ruta ha sido terminada'], ['tipomensaje' => 'success']);
        }
    }
    //status servicio ruta en 4 es reporgramnar
    //estatus servicio ruta en 3 es completa
    public function ModalReprogramarServicio($id)
    {
        $this->IdservicioReprogramar = $id;
    }

    public function ModalAceptarReprogramar()
    {
        $this->validate([
            'IdservicioReprogramar' => 'required',
            'motivoReprogramarConcepto' => 'required',
            'photorepro' => 'required',
        ]);
        $servicioRuta = RutaServicio::find($this->IdservicioReprogramar);
        $servicioRuta->status_ruta_servicios = 4;
        $servicioRuta->save();


        $rutaServicioReporte = new RutaServicioReporte();

        // Asignar valores del servicio actualizado al reporte
        $rutaServicioReporte->servicio_id = $servicioRuta->servicio_id;
        $rutaServicioReporte->ruta_id = $servicioRuta->ruta_id;
        $rutaServicioReporte->monto = $servicioRuta->monto;
        $rutaServicioReporte->folio = $servicioRuta->folio;
        $rutaServicioReporte->envases = $servicioRuta->envases;
        $rutaServicioReporte->tipo_servicio = $servicioRuta->tipo_servicio;
        $rutaServicioReporte->status_ruta_servicio_reportes = 4;
        $rutaServicioReporte->motivocancelacion = $this->motivoReprogramarConcepto;
        $rutaServicioReporte->area = 3;
        // Guardar el nuevo registro en la base de datos
        $rutaServicioReporte->save();
        $this->photorepro->storeAs(path: 'evidencias/reprogramacion/', name: 'avatar.png');
        $this->dispatch('agregarArchivocre', ['nombreArchivo' => 'El servicio Sera reprogramado'], ['tipomensaje' => 'success']);
    }
}
