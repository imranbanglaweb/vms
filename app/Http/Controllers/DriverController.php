<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Department;
use App\Models\Unit;
use App\Models\Employee;
use App\Models\Licnese_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
class DriverController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        $units = Unit::all();
        $employees = Employee::all();
        $drivers = Driver::latest()->get();

    return view('admin.dashboard.driver.index', compact('departments', 'units', 'employees', 'drivers'));
    }

    public function create(Request $request)
    {
          
         $units = Unit::orderBy('id','DESC')->get();
         $departments = Department::orderBy('id','DESC')->get();

         $units = Unit::orderBy('id','DESC')->get();
         $employees = Employee::orderBy('id','DESC')->get();
         // Load license types from table to populate dropdown
         $licenseTypes = Licnese_type::orderBy('type_name')->get();
        return view('admin.dashboard.driver.create',compact('departments','units','employees','licenseTypes'));


    }

    public function store(Request $request)
{  
    
    


    // ✅ Validation
    $validated = $request->validate([
        'unit_id'            => 'required|exists:units,id',
        'department_id'      => 'required|exists:departments,id',
        // 'employee_nid'       => 'required',
        'driver_name'        => 'required|string|max:255',
        'license_number'     => 'required|string|max:255|unique:drivers,license_number',
        'license_type_id'    => 'nullable|exists:licnese_types,id',
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

    // If a license_type_id was provided, map the readable name into the drivers.license_type column
    if (!empty($validated['license_type_id'])) {
        $lt = Licnese_type::find($validated['license_type_id']);
        if ($lt) {
            $validated['license_type'] = $lt->type_name;
        }
    }

// dd($validated);

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
                'mobile' => $employee->phone ?? '',
                'present_address' => $employee->present_address ?? '',
                'permanent_address' => $employee->permanent_address ?? '',
                'nid' => $employee->nid ?? '',
                'joining_date' => (!empty($employee->join_date) && ($employee->join_date instanceof \Carbon\Carbon)) ? $employee->join_date->format('Y-m-d') : ($employee->join_date ?? ''),
                'date_of_birth' => $employee->date_of_birth ?? '',
            ]);
        }
        return response()->json(['error' => 'Not found'], 404);
    }

    /**
     * Return HTML <option> list of departments for a given unit (used by driver create JS)
     */
    public function getDepartmentsByUnit(Request $request)
    {
        $unit_id = $request->get('unit_id');
        if (!$unit_id) {
            return response()->json(['department_list' => []]);
        }

        $departments = Department::where('unit_id', $unit_id)->orderBy('department_name')->get(['id', 'department_name']);

        return response()->json(['department_list' => $departments]);
    }

    /**
     * Return employee info by employee_nid (used by driver create JS)
     */


    public function data()
{
    if (request()->ajax()) {
        $drivers = Driver::select([
            'id',
            'unit_id',
            'department_id',
            'driver_name',
            'license_number',
            'nid',
            'employee_nid',
            'license_type',
            'license_issue_date',
            'date_of_birth',
            'joining_date',
            'present_address',
            'permanent_address',
            'mobile',
            'working_time_slot',
            'leave_status',
            'photograph'
        ])->latest();

        return DataTables::of($drivers)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                return '
                    <button class="btn btn-sm btn-primary editDriver" 
                        data-id="'.$row->id.'" 
                        data-name="'.$row->driver_name.'" 
                        data-phone="'.$row->mobile.'">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger deleteUser" 
                        data-did="'.$row->id.'">
                        <i class="fa fa-trash"></i>
                    </button>';
            })
            ->editColumn('driver_name', function($row){
                return $row->driver_name;
            })
            ->editColumn('mobile', function($row){
                return $row->mobile ?? '-';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
    public function getEmployeeInfo(Request $request)
    {
        $employee_code = $request->get('employee_code');
        if (!$employee_code) {
            return response()->json(['error' => 'Missing parameter'], 400);
        }

        $employee = Employee::where('employee_code', $employee_code)->first();
        if (!$employee) {
            // try fallback by employee_code if employee_nid not present
            $employee = Employee::where('employee_code', $employee_code)->first();
        }

        if (!$employee && is_numeric($employee_code)) {
            // last-resort: try by numeric id
            $employee = Employee::find((int)$employee_code);
        }

        if (!$employee) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json([
            'name' => $employee->name,
            'mobile' => $employee->phone ?? '',
            'present_address' => $employee->present_address ?? '',
            'permanent_address' => $employee->permanent_address ?? '',
            'employee_code' => $employee->employee_code ?? '',
            'joining_date' => (!empty($employee->join_date) && ($employee->join_date instanceof \Carbon\Carbon)) ? $employee->join_date->format('Y-m-d') : ($employee->join_date ?? ''),
            // Employee model may not have date_of_birth; include if present
            'date_of_birth' => $employee->date_of_birth ?? '',
            'nid' => $employee->nid ?? '',
        ]);
    }
}
