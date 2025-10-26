<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'driver_code',
        'name',
        'phone',
        'license_no',
        'license_type',
        'license_expiry',
        'nid_no',
        'address',
        'photo',

        'company_id',
        'status',
        'created_by',
        'updated_by'
    ];

    // ✅ Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function vehicle()
    {
        return $this->hasOne(Vehicle::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function travelRequests()
    {
        return $this->belongsToMany(TravelRequest::class, 'driver_travel_request')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
