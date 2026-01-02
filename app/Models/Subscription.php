<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    
    protected $fillable = [
        'company_id', 'plan_id', 'start_date', 'end_date', 'status'
    ];

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
