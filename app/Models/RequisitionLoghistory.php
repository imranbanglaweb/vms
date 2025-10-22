<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionLogHistory extends Model
{
    use SoftDeletes;
    protected $fillable = ['requisition_id','action_by','action_type','previous_status','new_status','remarks','action_date','created_by','updated_by'];
    public function requisition(){ return $this->belongsTo(Requisition::class); }
    public function actor(){ return $this->belongsTo(Employee::class,'action_by'); }
}
