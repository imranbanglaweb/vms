<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Department;
use App\Models\Unit;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class DriverController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        $units = Unit::all();
        $employees = Employee::all();
        $drivers = Driver::latest()->get();

        return view('drivers.index', compact('departments', 'units', 'employees', 'drivers'));
    }

    public function create(Request $request)
    {
          
         $units = Unit::orderBy('id','DESC')->get();
         $departments = Department::orderBy('id','DESC')->get();

         $units = Unit::orderBy('id','DESC')->get();
         $employees = Employee::orderBy('id','DESC')->get();
        return view('admin.dashboard.driver.create',compact('departments','units','employees'));


    }

    public function store(Request $request)
{
    // ✅ Validation
    $validated = $request->validate([
        'unit_id'            => 'required|exists:units,id',
        'department_id'      => 'required|exists:departments,id',
        'employee_nid'       => 'required',
        'driver_name'        => 'required|string|max:255',
        'license_number'     => 'required|string|max:255|unique:drivers,license_number',
        'license_type'       => 'nullable|string|max:100',
        'license_issue_date' => 'nullable|date',
        'joining_date'       => 'nullable|date',
        'date_of_birth'      => 'nullable|date',
        'nid'                => 'nullable|string|max:50',
        'mobile'             => 'nullable|string|max:20',
        'present_address'    => 'nullable|string|max:500',
        'permanent_address'  => 'nullable|string|max:500',
        'working_time_slot'  => 'nullable|string|max:50',
        'leave_status'       => 'nullable|boolean',
        'photograph'         => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
    ]);

    // ✅ Handle File Upload
    if ($request->hasFile('photograph')) {
        $file = $request->file('photograph');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('uploads/drivers'), $filename);
        $validated['photograph'] = 'uploads/drivers/'.$filename;
    }

    // ✅ Assign Created By (optional)
    $validated['created_by'] = auth()->id() ?? 1; // Change as needed

    // ✅ Save Driver
    $driver = \App\Models\Driver::create($validated);

    // ✅ Return JSON for AJAX
    return response()->json([
        'status'  => 'success',
        'message' => 'Driver added successfully!',
        'driver'  => $driver,
    ]);
}


    public function getEmployeeDetails($id)
    {
        $employee = Employee::find($id);
        if ($employee) {
            return response()->json([
                'driver_name' => $employee->name,
                'mobile' => $employee->mobile,
                'present_address' => $employee->present_address,
                'permanent_address' => $employee->permanent_address,
                'nid' => $employee->nid,
            ]);
        }
        return response()->json(['error' => 'Not found'], 404);
    }
}
