<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id', 'unit_id', 'department_id', 'employee_code', 'name', 'email', 'phone', 'employee_type', 'designation', 'blood_group', 'nid', 'photo', 'present_address', 'permanent_address', 'join_date', 'status'
    ];

    public function company() { return $this->belongsTo(Company::class); }
    public function unit() { return $this->belongsTo(Unit::class); }
    public function department() { return $this->belongsTo(Department::class); }
}
