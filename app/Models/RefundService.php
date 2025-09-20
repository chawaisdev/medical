<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundService extends Model
{
    protected $fillable = [
        'refund_id','service_id',
    ];

    public function refund()
    {
        return $this->belongsTo(Refund::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
