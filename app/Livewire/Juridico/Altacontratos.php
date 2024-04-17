<?php

namespace App\Livewire\Juridico;

use App\Models\Cliente;
use Livewire\Component;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;

class Altacontratos extends Component
{
    use WithPagination, WithoutUrlPagination; 
    public $clienteslis=[];
    public $clientenombre;
    protected $listeners = ['resetPagination'];

    public function mount()
    {
        $this->clientenombre = "";
    }

    public function render()
    {
        $data = $this->BuscarCliente();
        return view('livewire.juridico.altacontratos',compact('data'));
    }

    public function resetPagination()
    {
        // Reiniciar la paginación cuando sea necesario desde fuera del componente
        $this->resetPage();
    }

    public function BuscarCliente()
    {
        
        $valorbuscado = $this->clientenombre;

        if ($valorbuscado == "" || $valorbuscado == null) {
            $busqueda = []; // Paginar antes de obtener los resultados
        } else {
            $busqueda = Cliente::select('clientes.id', 'clientes.user_id', 'clientes.razon_social', 'users.name', 'users.paterno', 'users.materno')
                ->join('users', 'users.id', '=', 'clientes.user_id')
                ->where('clientes.status_cliente', 1)
                ->where(function ($query) use ($valorbuscado) {
                    $query->where('users.name', 'LIKE', '%' . $valorbuscado . '%')
                        ->orWhere('users.paterno', 'LIKE', '%' . $valorbuscado . '%')
                        ->orWhere('users.materno', 'LIKE', '%' . $valorbuscado . '%');
                })
                ->paginate(2); // Paginar antes de obtener los resultados
        }
        
        return $busqueda;
    }
}
