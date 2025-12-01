<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequisition extends Model
{
    protected $fillable = [
        'requisition_no',
        'requisition_type',
        'priority',
        'employee_id',
        'vehicle_id',
        'maintenance_type_id',
        'maintenance_date',
        'service_title',
        'charge_bear_by',
        'charge_amount',
        'remarks',
        'total_parts_cost',
        'total_cost',
        'status',
        'created_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function maintenanceType()
    {
        return $this->belongsTo(MaintenanceType::class);
    }

    public function items()
    {
        return $this->hasMany(MaintenanceRequisitionItem::class, 'requisition_id');
    }
}
