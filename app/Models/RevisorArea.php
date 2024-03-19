<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisorArea extends Model
{
    use HasFactory;

    protected $table = 'revisor_areas';
    protected $fillable = [
        'user_id',
        'ctg_area_id',
        'status_revisor_areas',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function area()
    {
        return $this->belongsTo(Ctg_Area::class, 'ctg_area_id');
    }
}
