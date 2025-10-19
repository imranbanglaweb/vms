<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

          protected $table = 'companies';

        protected $fillable = [
        'id',
        'company_name',
        'company_code',
        'unit_id',
        'company_oder',
        'remarks',
        'status',
        'created_by',
    ];
}
