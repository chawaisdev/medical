<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'appointment_id','patient_id','created_by_user_id','reason',
        'requested_amount','approved_amount','status','approved_by_user_id',
        'approved_at','processed_at','payment_method','transaction_reference','doctor_fee_refund'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    public function services()
    {
        return $this->hasMany(RefundService::class);
    }
}
