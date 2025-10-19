<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

         protected $table ='employees';

        protected $fillable = [
        'id',
        'unit_id',
        'company_id',
        'department_id',
        'department_head',
        'location_id',
        'employee_id',
        'employee_name',
        'employee_description',
        'designation',
        'employee_email',
        'employee_phone',
        'remarks',
        'employee_oder',
        'remarks',
        'status',
        'created_by',
    ];
}
