<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
     protected $fillable = [
        'name',
        'price',
    ];

    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'appointment_services', 'services_id', 'appointment_id')
                ->withTimestamps();
    }

    public function refundServices()
    {
        return $this->hasMany(RefundService::class, 'service_id');
    }

}
