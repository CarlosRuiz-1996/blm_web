<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemorandumValidacion extends Model
{
    use HasFactory;

    protected $table = 'memoranda_validacion';
    protected $fillable = [
        'revisor_areas_id',
        'memoranda_id',
        'status_validacion_memoranda',
    ];

    public function revisor_areas()
    {
        return $this->belongsTo(RevisorArea::class, 'revisor_areas_id');
    }


    public function memorandum()
    {
        return $this->belongsTo(Memorandum::class, 'memoranda_id');
    }
}
