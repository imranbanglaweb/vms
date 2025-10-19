<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

       protected $table = 'departments';

        protected $fillable = [
        'id',
        'unit_id',
        'company_id',
        'department_name',
        'department_code',
        'department_oder',
        'status',
        'created_by',
    ];
}
