<?php

namespace App\Livewire\Anexo1;


use Livewire\Component;
use App\Livewire\Forms\AnexoForm;
use App\Models\Cotizacion;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Session;

class GestionAnexo1 extends Component
{

    public AnexoForm $form;

    public $cotizacion;
    public $sucursal; //sucursal del modal

    //obtengo datos de cliente para pasarlos a la cabecera
    public function mount(Cotizacion $cotizacion)
    {
        $this->form->cliente_id = $cotizacion->cliente_id;
        $this->form->direcconFiscal();
    }

    public function render()
    {
        $servicios = $this->form->getAllServicios($this->cotizacion->id);

        return view('livewire.anexo1.gestion-anexo1', compact('servicios'));
    }


    public function validarCp()
    {

        $this->validate([
            'form.cp' => 'required|digits_between:1,5',
        ], [
            'form.cp.digits_between' => 'El código postal solo contiene 5 digitos.',
            'form.cp.required' => 'Código postal requerido.',

        ]);

        $this->form->validarCp();
    }

    #[On('save-sucursal')]
    public function save_sucursal()
    {
        $res = $this->form->store_sucursal();

        if ($res == 1) {
            $this->dispatch('success', ["La sucursal creo exitosamente.", 1]);
        } else {
            $this->dispatch('error', ["Ha ocurrido un error, intente más tarde."]);
        }
        $this->reset('sucursales');
    }


    //sucursales para el modal
    public $sucursales;
    public function getSucursales($servicio_id)
    {
        $this->limpiarDatos();
        $this->sucursales =  $this->form->getAllSucursal();
        $this->form->servicio_id = $servicio_id;
    }

    //detalles de la sucursal
    public $sucursal_show = false;
    public function getSucursal($sucursal_id)
    {
        $this->sucursal_show = true;
        $this->sucursales =  $this->form->getAllSucursalById($sucursal_id);
    }

    //relacionar sucursal y servicios
    public function sucursal_servicio()
    {


        $sucursal =  $this->form->getSucursalName();

        Session::push('servicio-sucursal', [
            'sucursal_id' => $this->form->sucursal_id,
            'servicio_id' => $this->form->servicio_id,
            'nombre' => $sucursal->sucursal
        ]);
        $this->limpiarDatos();

        $this->dispatch('alert-sucursal-add', "La sucursal sucursal se le agrego al servicio");
    }

    //editar sucursales:
    public $selectedServicioId; // Agrega esta propiedad al componente para almacenar el ID del servicio seleccionado para la edición
    public $selectedSucursalId;
    public function getSucursalesForEdit($servicioId, $sucursalId)
    {
        $this->getSucursales($servicioId);
        $this->selectedServicioId = $servicioId;
        $this->selectedSucursalId = $sucursalId;
    }

    public function actualizarSucursalServicio()
    {
        $index = null;
        if (Session::has('servicio-sucursal')) {
            $serviciosSucursal = Session::get('servicio-sucursal');
            foreach ($serviciosSucursal as $key => $servicioSucursal) {
                if ($servicioSucursal['servicio_id'] == $this->selectedServicioId) {
                    $index = $key;
                    break;
                }
            }
        }

        // Si se encuentra el servicio en el arreglo de sesión, actualiza los valores correspondientes
        if ($index !== null) {
            $sucursal =  $this->form->getSucursalName();
            $nuevaSucursalId = $this->form->sucursal_id;

            $nuevoNombreSucursal =  $sucursal->sucursal;

            Session::put('servicio-sucursal.' . $index . '.sucursal_id', $nuevaSucursalId);
            Session::put('servicio-sucursal.' . $index . '.nombre', $nuevoNombreSucursal);
        }
        $this->limpiarDatos();

        $this->dispatch('alert-sucursal-add', "La sucursal se modifico");
    }

    public function eliminarSucursalServicio($servicioId)
    {
        // Encuentra el índice del servicio en el arreglo de sesión
        $index = null;
        if (Session::has('servicio-sucursal')) {
            $serviciosSucursal = Session::get('servicio-sucursal');
            foreach ($serviciosSucursal as $key => $servicioSucursal) {
                if ($servicioSucursal['servicio_id'] == $servicioId) {
                    $index = $key;
                    break;
                }
            }
        }

        // Si se encuentra el servicio en el arreglo de sesión, elimina el registro
        if ($index !== null) {
            Session::forget('servicio-sucursal.' . $index);
        }
        $this->limpiarDatos();
        $this->dispatch('sucursal-delete', "La sucursal se elimino");
    }

    #[On('limpiar')]
    public function limpiarDatos()
    {
        $this->form->sucursal_id = '';
        $this->form->servicio_id = '';
        $this->selectedServicioId = '';
        $this->selectedSucursalId = '';
        $this->sucursales = '';
        $this->form->sucursal = '';
        $this->form->correo = '';
        $this->form->phone = '';
        $this->form->contacto = '';
        $this->form->cargo = '';
        $this->form->direccion_completa = '';
    }
    #[On('save-servicios')]
    public function save()
    {
        if (Session::has('servicio-sucursal')) {
            $res = $this->form->store($this->cotizacion);
            if ($res == 1) {
                session()->forget('servicio-sucursal');
                $this->dispatch('success', ["Anexo completado con exito."]);
            } else {
                $this->dispatch('error', ["A ocurrido un error, intente más tarde.", 1]);
            }
        } else {
            session()->forget('servicio-sucursal');
            $this->dispatch('error', ["No hay servicios con sucursales."]);
        }
    }


    public function updating($property, $value)
    {
        if ($property === 'form.fecha_evaluacion') {

            if ($this->form->fecha_inicio_servicio) {
                if ($value > $this->form->fecha_inicio_servicio) {
                    $this->addError('form.fecha_evaluacion', 'La fecha de evaluación debe ser menor a la fecha de inicio de servicio.');
                } else {
                    $this->resetValidation('form.fecha_evaluacion');
                }
            }
        }
        if ($property === 'form.fecha_inicio_servicio') {
            if ($this->form->fecha_evaluacion) {
                if ($value < $this->form->fecha_evaluacion) {
                    $this->addError('form.fecha_inicio_servicio', 'No se puede iniciar el servicio sin evaluación.');
                } else {
                    $this->resetValidation('form.fecha_inicio_servicio');
                }
            }
        }
    }
}
