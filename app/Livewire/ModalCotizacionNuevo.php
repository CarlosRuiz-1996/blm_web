<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\User;
use Livewire\Component;

class ModalCotizacionNuevo extends Component
{
    public $nombrecliente;
    public $idseleccionado='';
    public $sugerencias = [];

    public function updatedNombreCliente($value) 
    {
        if ($value) {
            $this->sugerencias = Cliente::select('clientes.id', 'clientes.user_id', 'users.name', 'users.paterno', 'users.materno')
                ->join('users', 'users.id', '=', 'clientes.user_id')
                ->whereRaw("CONCAT(users.name, ' ', users.paterno, ' ', users.materno) LIKE ?", ['%'.$value.'%'])
                ->get();
        } else {
            $this->sugerencias = [];
        }
    }
        
        

    public function seleccionarCliente($id,$cliente)
    {
        $this->nombrecliente = $cliente;
        $this->idseleccionado = $id;
        $this->sugerencias = []; // Limpiar las sugerencias después de seleccionar un cliente.
    }
    public function seleccionarClienteurl($value)
    {
        // Verificar si se ha seleccionado un cliente
        if ($value) {
            // Redirigir a otra vista con el idseleccionado en la URL
            return redirect()->route('clientesactivos.cotizardenuevo', ['id' => $value]);
        }

        // Si no se ha seleccionado un cliente, puedes realizar otras acciones o redirigir a otra vista por defecto
        return redirect()->route('otra_ruta');
    }
    public function render()
    {
        return view('livewire.modal-cotizacion-nuevo');
    }
}
