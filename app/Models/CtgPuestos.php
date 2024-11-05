<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtgPuestos extends Model
{
    use HasFactory;
    protected $table = "ctg_puestos";
    protected $fillable = ["puesto", 'ctg_area'];
}
