<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ctg_Tipo_Cliente extends Model
{
    use HasFactory;
    protected $table = 'ctg_tipo_cliente';
    protected $fillable = [
        'name','status_ctg_tipo_cliente'
    ];
}
