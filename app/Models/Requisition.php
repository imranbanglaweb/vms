<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requisition extends Model
{
    use SoftDeletes;
    protected $fillable = [
      'requested_by',
      'company_id',
      'unit_id',
      'department_id',
      'from_location',
      'to_location',
      'purpose',
      'travel_date',
      'travel_time',
      'return_date',
      'vehicle_id',
      'driver_id',
      'requisition_date',
      'status',
      'approval_level_1_by',
      'approval_level_1_date',
      'approval_level_2_by',
      'approval_level_2_date',
      'total_passenger_count',
      'created_by',
      'updated_by'
    ];

    protected $casts = [
      'travel_date'=>'date','travel_time'=>'datetime','return_date'=>'datetime',
      'approval_level_1_date'=>'datetime','approval_level_2_date'=>'datetime'
    ];

    public function creator(){ return $this->belongsTo(Employee::class,'requested_by'); }
    public function passengers(){ return $this->hasMany(RequisitionPassenger::class); }
    public function approvals(){ return $this->hasMany(RequisitionApproval::class); }
    public function vehicle(){ return $this->belongsTo(Vehicle::class); }
    public function driver(){ return $this->belongsTo(Driver::class); }
    public function logs(){ return $this->hasMany(RequisitionLogHistory::class); }
    public function notifications(){ return $this->hasMany(Notification::class); }
    public function requestedBy(){return $this->belongsTo(Employee::class, 'requested_by', 'id');}

    public function workflowLogs()
{
    return $this->hasMany(WorkflowLog::class);
}
 // Badge Color Helper
    public function statusBadgeColor()
    {
        return match($this->status) {
            'Pending' => 'warning',
            'Approved' => 'success',
            'Rejected' => 'danger',
            'Completed' => 'info',
            default => 'secondary',
        };
    }

    
}
