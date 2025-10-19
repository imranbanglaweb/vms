<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

        protected $table ='locations';

        protected $fillable = [
        'id',
        'location_name',
        'address',
        'unit_id',
        'company_id',
        'location_oder',
        'remarks',
        'status',
        'created_by',
    ];
}
