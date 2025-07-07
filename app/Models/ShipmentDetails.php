<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentDetails extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shipment_id',
        'item_name',
        'quantity',
        'weight',
        'dimensions',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];
}
