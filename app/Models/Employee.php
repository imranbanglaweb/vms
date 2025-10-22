<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'company_id','unit_id','department_id','employee_code','employee_name','designation',
      'employee_type','email','phone','blood_group','nid','photo','date_of_birth','joining_date',
      'present_address','permanent_address','status','created_by','updated_by'
    ];

    protected $casts = [
      'date_of_birth' => 'date',
      'joining_date' => 'date',
    ];

    public function company(){ return $this->belongsTo(Company::class); }
    public function unit(){ return $this->belongsTo(Unit::class); }
    public function department(){ return $this->belongsTo(Department::class); }
    public function requisitions(){ return $this->hasMany(Requisition::class,'requested_by'); }
    public function passengerFor(){ return $this->hasMany(RequisitionPassenger::class,'employee_id'); }
}
