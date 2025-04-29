<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anuncio extends Model
{
    protected $table = 'anuncios';

    protected $fillable = [
        'titulo',
        'texto',
        'tipo',
        'media',
        'link',
    ];

    /**
     * Accesor para obtener la URL completa del media
     */
    public function getMediaUrlAttribute()
    {
        return asset('storage/' . $this->media);
    }
}
