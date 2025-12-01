<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'category_name',
        'category_slug',
        'category_type',
        'category_oder',
        'status',
        'created_by',
        'updated_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // 🔹 Self Relationship (Parent Category)
    public function parent()
    {
        return $this->belongsTo(MaintenanceCategory::class, 'parent_id');
    }

    // 🔹 Self Relationship (Sub Categories / Children)
    public function children()
    {
        return $this->hasMany(MaintenanceCategory::class, 'parent_id');
    }

    // 🔹 Relationship with Requisition Items
    // Your item migration uses: category_id
    public function requisitionItems()
    {
        return $this->hasMany(MaintenanceRequisitionItem::class, 'category_id');
    }

    // 🔹 Created By User
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // 🔹 Updated By User
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
