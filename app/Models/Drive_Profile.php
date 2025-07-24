<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drive_Profile extends Model
{
    //
    protected $table = 'drive__profiles';

    protected $fillable = [
        'name',
        'email',
        "documents",
        "city",
        "national_ID",
         "phone",
         'driver_id',
        'birth_date'
    ];
 

}
