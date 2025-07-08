<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ShipmentDetails;
use App\Models\Offers;


class Shipment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Client_id',
        'Driver_id',
    ];

    public function details()
    {
        return $this->hasOne(ShipmentDetails::class, 'shipment_id');
    }

    public function offers()
    {
    return $this->hasMany(Offers::class, 'shipment_id');
    }

}
