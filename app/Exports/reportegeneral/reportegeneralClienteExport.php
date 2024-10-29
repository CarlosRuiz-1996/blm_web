<?php

namespace App\Exports\reportegeneral;

use App\Models\Cliente;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class reportegeneralClienteExport implements FromView
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $cliente = Cliente::findOrFail($this->id);
        return view('excelreportegeneral.cliente', compact('cliente'));
    }
    
}
