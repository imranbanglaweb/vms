<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
      protected $table = 'payments';
        protected $fillable = [
        'id',
        'company_id', 
        'user_id',
        'subscription_id',
        'plan_id',
        'method',
        'amount',
        'currency',
        'transaction_id',
        'note',
        'status',
        'created_by',
    ];

      public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
     public function user()
    {
        return $this->belongsTo(User::class);
    }

      public function subscriptionPlan()
    {
          return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }
}
