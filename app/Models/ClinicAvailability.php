<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicAvailability extends Model
{
    protected $table = "clinic_availabilities";

    protected $fillable = [
        'is_active',
        'day',
        'start_time',
        'end_time',
    ];

}
