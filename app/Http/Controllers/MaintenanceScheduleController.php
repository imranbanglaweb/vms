<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceSchedule;
use App\Models\Vehicle;
use App\Models\MaintenanceType;
use App\Models\MaintenanceVendor;

class MaintenanceScheduleController extends Controller
{
    public function index()
    {
        $schedules = MaintenanceSchedule::latest()->get();
        $vehicles = Vehicle::all();
        $types = MaintenanceType::all();
        $vendors = MaintenanceVendor::all();
        return view('admin.dashboard.maintenance.schedules.index', compact('schedules','vehicles','types','vendors'));
    }

    public function create()
    {
        $vehicles = Vehicle::all();
        $types = MaintenanceType::all();
        $vendors = MaintenanceVendor::all();
        return view('admin.dashboard.maintenance.schedules.create', compact('vehicles','types','vendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'vehicle_id' => 'required|exists:vehicles,id',
            'maintenance_type_id' => 'required|exists:maintenance_types,id',
            'scheduled_at' => 'required|date',
        ]);

          $data = $request->only([
        'vehicle_id','maintenance_type_id','vendor_id','title',
        'scheduled_at','next_due_date','due_km','frequency','notes','active'
    ]);

    // Auto-calc next_due_date if frequency provided and next_due_date empty
    if (!empty($data['frequency']) && empty($data['next_due_date'])) {
        $data['next_due_date'] = $this->calcNextDueDate($data['scheduled_at'], $data['frequency']);
    }

        // MaintenanceSchedule::create($request->all());
    $schedule = MaintenanceSchedule::create($data + ['created_by' => Auth()->id() ?? 0]);
        

    if ($request->wantsJson() || $request->ajax()) {
        return response()->json(['message' => 'Schedule created', 'schedule' => $schedule], 201);
    }

        return response()->json(['status'=>'success','message'=>'Schedule added successfully.']);
    }

    public function edit(MaintenanceSchedule $schedule)
    {
        return response()->json($schedule);
    }

    public function update(Request $request, MaintenanceSchedule $schedule)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'vehicle_id' => 'required|exists:vehicles,id',
            'maintenance_type_id' => 'required|exists:maintenance_types,id',
            'scheduled_at' => 'required|date',
        ]);

        // $schedule->update($request->all());

         $schedule = MaintenanceSchedule::findOrFail($id);

    $data = $req->only([
        'vehicle_id','maintenance_type_id','vendor_id','title',
        'scheduled_at','next_due_date','due_km','frequency','notes','active'
    ]);

    if (!empty($data['frequency']) && empty($data['next_due_date'])) {
        $data['next_due_date'] = $this->calcNextDueDate($data['scheduled_at'], $data['frequency']);
    }

    $data['updated_by'] = auth()->id() ?? 0;
    $schedule->update($data);

    if ($req->wantsJson() || $req->ajax()) {
        return response()->json(['message' => 'Schedule updated', 'schedule' => $schedule], 200);
    }

        return response()->json(['status'=>'success','message'=>'Schedule updated successfully.']);
    }

    public function destroy(MaintenanceSchedule $schedule)
    {
        $schedule = MaintenanceSchedule::findOrFail($id);
        $schedule->delete();

           if ($req->wantsJson() || $req->ajax()) {
        return response()->json(['message' => 'Schedule deleted'], 200);
    }

    // return back()->with('success','Deleted');
        return response()->json(['status'=>'success','message'=>'Schedule deleted successfully.']);
    }

    public function toggleActive(Request $req, $id)
    {
        $s = MaintenanceSchedule::findOrFail($id);
        $s->active = !$s->active;
        $s->save();

        return response()->json(['message'=>'Status updated','active'=>$s->active]);
    }

/**
 * Utility: parse frequency string into next due date.
 * Supports: "3 months", "6 months", "1 year", "30 days" (case-insensitive)
 */
    protected function calcNextDueDate($baseDate, $frequency)
    {
    try {
        $freq = trim(strtolower($frequency));
        if (preg_match('/(\d+)\s*month/', $freq, $m)) {
            return date('Y-m-d', strtotime($baseDate. ' +'.intval($m[1]).' months'));
        }
        if (preg_match('/(\d+)\s*year/', $freq, $m)) {
            return date('Y-m-d', strtotime($baseDate. ' +'.intval($m[1]).' years'));
        }
        if (preg_match('/(\d+)\s*day/', $freq, $m)) {
            return date('Y-m-d', strtotime($baseDate. ' +'.intval($m[1]).' days'));
        }
        // fallback: try to strtotime the string directly
        $computed = strtotime("+{$frequency}", strtotime($baseDate));
        if ($computed) return date('Y-m-d', $computed);
    } catch (\Throwable $e) {
        // ignore and return null
    }
    return null;
    }




}

