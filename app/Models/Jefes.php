<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jefes extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'apellido', 'telefono', 'correo','area_id'
        ];

        public function area()
        {
            return $this->belongsTo(Areas::class);
        }
}
