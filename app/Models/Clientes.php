<?php

namespace App\Models;

use Eloquent as Model;


class Clientes extends Model
{

    public $table = 'clientes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'orden_id',
        'nombre',
        'apellido',
        'email',
    ];


    protected $casts = [
        'id' => 'integer',
    ];

    public static $rules = [
        
    ];
    public function ordenesId()
    {
        return $this->belongsTo(\App\Models\Ordenes::class, 'orden_id');
    }

}
