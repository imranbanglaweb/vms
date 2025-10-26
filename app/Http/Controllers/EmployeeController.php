<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::with(['unit','department','company'])->orderBy('id','ASC')->get();

        // Normalize to the view's expected keys (some views expect employee_id/employee_name etc.)
        $employee_lists = $employees->map(function($e){
            return (object)[
                'id' => $e->id,
                'emp_id' => $e->id,
                'employee_id' => $e->employee_code ?? $e->id,
                'employee_name' => $e->name ?? '',
                'unit_name' => optional($e->unit)->unit_name ?? '',
                'department_name' => optional($e->department)->department_name ?? '',
                'location_name' => '',
            ];
        });

        return view('admin.dashboard.employee.index',compact('employee_lists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $units = Unit::orderBy('unit_name')->get();
        return view('admin.dashboard.employee.create', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'unit_id' => 'required|exists:units,id',
            'company_id' => 'nullable|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'employee_code' => 'nullable|string|max:50|unique:employees,employee_code',
            'name' => 'required|string|max:191',
            'email' => 'nullable|email|max:191',
            'phone' => 'nullable|string|max:50',
            'employee_type' => 'nullable|in:Permanent,Contract,Intern',
            'designation' => 'nullable|string|max:100',
            'blood_group' => 'nullable|string|max:10',
            'nid' => 'nullable|string|max:50',
            'photo' => 'nullable|image|max:2048',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'join_date' => 'nullable|date',
            'status' => 'required|in:Active,Inactive',
        ];
        try {
            $validated = $request->validate($rules);

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/employees', $filename, 'public');
                $validated['photo'] = 'storage/'.$path;
            }

            $employee = Employee::create($validated);

            // Return JSON for AJAX, or redirect for normal requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Employee created', 'employee' => $employee], 201);
            }

            return redirect()->route('employees.index')->with('success', 'Employee created successfully');

        } catch (ValidationException $e) {
            // Return JSON with validation errors for AJAX
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $e->validator->errors(),
                ], 422);
            }

            throw $e; // fallback to default behavior
        }
        // Return JSON for AJAX, or redirect for normal requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Employee created', 'employee' => $employee], 201);
        }

        return redirect()->route('employees.index')->with('success', 'Employee created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
