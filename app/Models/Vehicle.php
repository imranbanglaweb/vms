<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_code',
        'registration_no',
        'registration_date',
        'brand',
        'model',
        'type',
        'capacity',
        'color',
        'fuel_type',
        'purchase_date',
        'status_id',
        'company_id',
        'unit_id',
        'department_id',
        'location_id',
        'driver_id',

        'status',
        'created_by',
        'updated_by'
    ];

    // ✅ Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function fuelLogs()
    {
        return $this->hasMany(FuelLog::class);
    }

    public function maintenanceRecords()
    {
        return $this->hasMany(Maintenance::class);
    }
}
