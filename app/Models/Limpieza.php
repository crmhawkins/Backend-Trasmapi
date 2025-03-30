<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Limpieza extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'puntuacion'];

    protected $dates = ['created_at', 'updated_at'];
}
