<?php

namespace App\Livewire\Forms;

use App\Models\MemorandumValidacion;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MemoValidacionForm extends Form
{
    public function getAll(){
        return MemorandumValidacion::all();
    }
}
