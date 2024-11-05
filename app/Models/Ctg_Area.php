<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ctg_Area extends Model
{
    use HasFactory;
    protected $table = 'ctg_area';
    protected $fillable = [
        'name','status_area'
    ];
}
