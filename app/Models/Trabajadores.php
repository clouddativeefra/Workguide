<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Jefes;

class Trabajadores extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'apellido', 'telefono', 'correo','jefe_id'
        ];

        public function jefe()
        {
            return $this->belongsTo(Jefes::class,'jefe_id');
        }
}

