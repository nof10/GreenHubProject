<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drive_Profile extends Model
{
    //

    protected $fillable = [
        'name',
        'email',
        "documents",
        "gender",
        "city",
        "birth_date",
       "national_ID",
         "phone"

    ];
 

}
