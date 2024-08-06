<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividades extends Model
{
    use HasFactory;

    protected $table = 'actividades';

    protected $fillable = [
        'titulo',
        'trabajadores_id',
        'descripcion',
        'ayuda',
    ];


    public function trabajadores()
    {
        return $this->belongsTo(Trabajadores::class, 'trabajadores_id');
    }
}
