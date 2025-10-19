<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
class Category extends Model
{
    use HasFactory;


     protected $table = 'categories';
    // protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'category_name',
        'category_slug',
        'parent_id',
        'department_id',
        'unit_id',
        'location_id',
        'status',
        'created_by',
    ];

  // public function categoryNameByTitle(){

  //       return $this->belongsTo(Category::class,'category_id');
  //   }

    
}
