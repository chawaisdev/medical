<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSchedule extends Model
{
    protected $table = 'user_schedules'; // Specify the table name if different from the default
    protected $fillable = [
        'user_id',
        'day',
        'start_time',
        'end_time',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
