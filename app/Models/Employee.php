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

    // public function company() { return $this->belongsTo(Company::class); }
   // Unit relationship
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id'); // use correct foreign key
    }
    // public function department() { return $this->belongsTo(Department::class); }

       public function department()
    {
        return $this->belongsTo(Department::class, 'department_id'); // use correct foreign key
    }
public function officeLocation() { return $this->belongsTo(Location::class, 'office_location_id'); }

}
