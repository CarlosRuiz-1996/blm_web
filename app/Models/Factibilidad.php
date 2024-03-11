<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factibilidad extends Model
{
    use HasFactory;
    protected $table = 'factibilidad';
    protected $fillable = [
        'cliente_id',
        'user_id',
        'status_factibilidad',
        'anexo1_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
