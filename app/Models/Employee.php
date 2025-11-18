<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\Unit;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id', 'unit_id', 'department_id', 'employee_code', 'name', 'email', 'phone', 'employee_type', 'designation', 'blood_group', 'nid', 'photo', 'present_address', 'permanent_address', 'join_date', 'status'
    ];

   public function department()
{
    return $this->belongsTo(Department::class, 'department_id', 'id');
}

public function unit()
{
    return $this->belongsTo(Unit::class, 'unit_id', 'id');
}
public function officeLocation() { return $this->belongsTo(Location::class, 'office_location_id'); }

}
