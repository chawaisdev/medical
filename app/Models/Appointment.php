<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
     protected $fillable = [
        'doctor_id',
        'patient_id',
        'date',
        'time',
        'final_fee',
        'discount',
        'fee',
        'additional_charges',
        'note',
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    protected $dates = ['date', 'time'];

 public function services()
    {
        return $this->belongsToMany(Service::class, 'appointment_services', 'appointment_id', 'services_id')
                    ->withTimestamps();
    }
}
