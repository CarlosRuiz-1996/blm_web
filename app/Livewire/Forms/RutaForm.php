<?php

namespace App\Livewire\Forms;

use App\Models\CtgRutas;
use Livewire\Attributes\Validate;
use Livewire\Form;
class RutaForm extends Form
{
    
    public function getCtgRutas(){
        return CtgRutas::all();
    }
}
