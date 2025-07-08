<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorate_dest extends Model
{
    protected $table = 'favorate_dest';

    protected $fillable = ['client_id', 'destination', 'address'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}