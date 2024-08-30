<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MontoBlm extends Model
{
    use HasFactory;
    protected $fillable = ['monto'];
    protected $table = "monto_blms";
}
