<?php

namespace App\Livewire\Clientes\Modals;

use App\Livewire\Forms\AnexoForm;
use App\Livewire\Forms\MemorandumForm;
use App\Models\Cliente;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\Attributes\On;

class AnexoServicios extends Component
{
    public AnexoForm $form;
    public MemorandumForm $form_memo;

    public function render()
    {

        $clientes = Cliente::where('status_cliente',1)->get();

        return view('livewire.clientes.modals.anexo-servicios', [
            'ctg_tipo_solicitud' => $this->form_memo->getAllTipoSolicitud(),
            'ctg_tipo_servicio' => $this->form_memo->getAllTipoServicio(),
            'ctg_horario_entrega' => $this->form_memo->getAllHorarioEntega(),
            'ctg_dia_entrega' => $this->form_memo->getAllDiaEntega(),
            'ctg_horario_servicio' => $this->form_memo->getAllHorarioServicio(),
            'ctg_dia_servicio' => $this->form_memo->getAllDiaServicio(),
            'ctg_consignatario' => $this->form_memo->getAllConsignatorio(),
            'clientes'=>$clientes


        ]);
    }

    public function mount(Cliente $cliente)
    {
        $this->form->cliente_id = $cliente->id;
        $this->sucursales =  $this->form->getAllSucursal();
    }

    //sucursales para el modal
    public $sucursales;
    // public function getSucursales()
    // {
    //     dd($this->sucursales);
    // }
    protected $listeners = ['open-sucursal-servico-clienteActivo' => 'handleOpen'];

    public function handleOpen()
    {
        $this->dispatch('open-sucursal');
    }

    // Session::push('servicioForaneo', [
    public function sucursal_servicio()
    {


        $this->form->getSucursalName();

        // dd($sucursal);
        Session::push('servicio-sucursal', [
            'sucursal_id' => $this->form->sucursal_id,
        ]);
        $this->dispatch('sucursal-servico-memorandum');
    }


    #[On('save-sucursal-servicio')]
    public function save_sucursal()
    {
        $res = $this->form->store_sucursal();

        if ($res == 1) {
            $this->dispatch('success', ["La sucursal creo exitosamente.", 1]);
            $this->sucursales =  $this->form->getAllSucursal();
            $this->dispatch('resetSelect2');
        } else {
            $this->dispatch('error', ["Ha ocurrido un error, intente más tarde.", 1]);
        }
    }


    public function terminar()
    {
        Session::push('servicio-memo', [
            'grupo' => $this->form_memo->grupo,
            'ctg_tipo_solicitud' => $this->form_memo->ctg_tipo_solicitud_id,
            'ctg_tipo_servicio' => $this->form_memo->ctg_tipo_servicio_id,
            'horarioEntrega' => $this->form_memo->horarioEntrega,
            'diaEntrega' => $this->form_memo->diaEntrega,
            'horarioServicio' => $this->form_memo->horarioServicio,
            'diaServicio' => $this->form_memo->diaServicio,
            'consignatorio' => $this->form_memo->consignatorio,
        ]);

        $this->dispatch('close-memo');

        $this->dispatch('cliente-servicio-fin');
    }


    #[On('clean-servicio-memo-anexo')]
    public function clean()
    {
        session()->forget('servicio-sucursal');
        session()->forget('servicio-memo');
        $this->dispatch('clean-servicio');

        $this->reset(
            'form.sucursal_id',
            'form_memo.horarioEntrega',
            'form_memo.grupo',
            'form_memo.ctg_tipo_solicitud_id',
            'form_memo.ctg_tipo_servicio_id',
            'form_memo.diaEntrega',
            'form_memo.horarioServicio',
            'form_memo.diaServicio',
            'form_memo.consignatorio'
        );
    }

    public function validarCp()
    {

        // dd('entra');
        $this->validate([
            'form.cp' => 'required|digits_between:1,5',
        ], [
            'form.cp.digits_between' => 'El código postal solo contiene 5 digitos.',
            'form.cp.required' => 'Código postal requerido.',

        ]);

        $this->form->validarCp();
    }
}
