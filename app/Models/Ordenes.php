<?php

namespace App\Models;

use Eloquent as Model;


class Ordenes extends Model
{

    public $table = 'ordenes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'orderId',
        'total_amount',
        'payment_method',
        'procesado',
    ];


    protected $casts = [
        'id' => 'integer',
    ];

    public static $rules = [
        
    ];
    public function clientesId()
    {
        return $this->hasMany(\App\Models\Clientes::class, 'orden_id');
    }
    public function itemsId()
    {
        return $this->hasMany(\App\Models\Items::class, 'orden_id');
    }
}
