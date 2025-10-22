<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use SoftDeletes;
    protected $fillable = [
      'company_id','unit_id','department_id','license_type_id','driver_name','license_number','nid','employee_nid',
      'working_time_slot','date_of_birth','present_address','permanent_address','mobile','license_issue_date',
      'joining_date','photograph','leave_status','status','created_by','updated_by'
    ];
    protected $casts = ['date_of_birth'=>'date','license_issue_date'=>'date','joining_date'=>'date'];

    public function licenseType(){ return $this->belongsTo(LicenseType::class,'license_type_id'); }
    public function company(){ return $this->belongsTo(Company::class); }
    public function unit(){ return $this->belongsTo(Unit::class); }
    public function department(){ return $this->belongsTo(Department::class); }
    public function documents(){ return $this->hasMany(DriverDocument::class); }
    public function trips(){ return $this->hasMany(Requisition::class,'driver_id'); }
}
