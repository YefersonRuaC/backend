<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'categoria',
        'vencimiento',
        'imagen',
        'user_id'
    ];

    //Relacion de Note con el modelo de User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
