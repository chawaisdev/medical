<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentServices extends Model
{
    protected $table = 'appointment_services';
    protected $fillable = [
        'appointment_id',
        'service_id',
    ];
}
