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
        'type',
        'size',
        'weight',
        'destination',
        'address',
        'scheduled_date',
        'scheduled_time',
        'status',
        'is_immediate',
        'payment_method',
        'summary',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];
}
