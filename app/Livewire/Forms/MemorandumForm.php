<?php

namespace App\Livewire\Forms;

use App\Models\CtgTipoServicio;
use App\Models\CtgTipoSolicitud;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MemorandumForm extends Form
{
    public function getAllTipoSolicitud(){
        return CtgTipoSolicitud::all();
    }

    public function getAllTipoServicio(){
        return CtgTipoServicio::all();
    }
}
