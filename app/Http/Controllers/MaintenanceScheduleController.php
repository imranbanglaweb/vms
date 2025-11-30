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

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'vehicle_id' => 'required|exists:vehicles,id',
            'maintenance_type_id' => 'required|exists:maintenance_types,id',
            'scheduled_at' => 'required|date',
        ]);

        MaintenanceSchedule::create($request->all());

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

        $schedule->update($request->all());

        return response()->json(['status'=>'success','message'=>'Schedule updated successfully.']);
    }

    public function destroy(MaintenanceSchedule $schedule)
    {
        $schedule->delete();
        return response()->json(['status'=>'success','message'=>'Schedule deleted successfully.']);
    }
}

