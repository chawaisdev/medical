<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientReport extends Model
{
    protected $table = 'patient_reports';

    protected $fillable = [
        'user_id',
        'date',    
        'reports',  
        'title',
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
