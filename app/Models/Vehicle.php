<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;
    protected $fillable = [
      'company_id','unit_id','department_id','vehicle_number','registration_date','vehicle_type_id',
      'brand','model','capacity','color','fuel_type','engine_number','chassis_number','registration_certificate',
      'availability','status','purchase_date','last_service_date','next_service_due','insurance_expiry','photo',
      'remarks','created_by','updated_by'
    ];

    protected $casts = ['registration_date'=>'date','purchase_date'=>'date','last_service_date'=>'date','next_service_due'=>'date','insurance_expiry'=>'date'];

    public function company(){ return $this->belongsTo(Company::class); }
    public function unit(){ return $this->belongsTo(Unit::class); }
    public function department(){ return $this->belongsTo(Department::class); }
    public function vehicleType(){ return $this->belongsTo(VehicleType::class); }
    public function maintenance(){ return $this->hasMany(VehicleMaintenanceRecord::class); }
    public function trips(){ return $this->hasMany(Requisition::class,'vehicle_id'); }
}
