<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_name',
        'unit_id',
        'department_id',
        'license_plate',
        'vehicle_type_id',
        'driver_id',
        'vendor_id',
        'registration_date',
        'seat_capacity',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * Vehicle belongs to a Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Vehicle belongs to a Driver
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    /**
     * Vehicle belongs to a Vehicle Type
     */
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    /**
     * Vehicle belongs to a Vendor
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    /**
     * Vehicle belongs to a Unit
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
