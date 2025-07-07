<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    //
    protected $fillable = [
        'shipment_id',
        'driver_id',
        'price',
        'status',
    ];
}
