<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Trabajadores;

class Tareas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'cantidad_trabajadores', 'descripcion', 'ayuda', 'trabajadores_id',
        ];

        public function trabajadores()
        {
            return $this->belongsTo(Trabajadores::class,'trabajadores_id');
        }
}
