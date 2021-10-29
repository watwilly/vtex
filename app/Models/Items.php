<?php

namespace App\Models;

use Eloquent as Model;


class Items extends Model
{

    public $table = 'items';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'orden_id',
        'refId',
        'productId',
        'name',
        'quantity',
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
