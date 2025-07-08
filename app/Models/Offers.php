<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Driver;
use App\Models\Shipment;
class Offers extends Model
{
    //
    protected $fillable = [
        'shipment_id',
        'driver_id',
        'price',
        'status',
    ];

    public function shipment()
    {
    return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    public function driver()
    {
    return $this->belongsTo(Driver::class, 'driver_id');
    }


}
