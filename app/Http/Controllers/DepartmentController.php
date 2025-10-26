<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Unit;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Server-side data endpoint for departments (DataTables)
     */
    public function data(Request $request)
    {
        $query = DB::table('departments')
            ->leftJoin('units','departments.unit_id','units.id')
            ->select('departments.id as id','departments.department_name','departments.department_code','departments.description','units.unit_name');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $edit = route('departments.edit', $row->id);
                $btn = "<a class='btn btn-sm btn-primary' href='{$edit}'><i class='fa fa-edit'></i></a> ";
                $btn .= "<button class='btn btn-sm btn-danger deleteUser' data-did='".$row->id."'><i class='fa fa-minus-circle'></i></button>";
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        //
    }

    /**
     * Return companies related to a unit (for AJAX select population).
     */
    public function unitWiseCompany(Request $request)
    {
        $unit_id = $request->get('unit_id');

        // Try to infer company from Unit if possible, otherwise return all companies
        $companies = collect();
        if ($unit_id) {
            $unit = Unit::find($unit_id);
            if ($unit && isset($unit->company_id) && $unit->company_id) {
                $companies = Company::where('id', $unit->company_id)->get(['id', 'name']);
            }
        }

        if ($companies->isEmpty()) {
            $companies = Company::select('id', 'name')->orderBy('name')->get();
        }

        // Normalize key expected by front-end
        $company_list = $companies->map(function ($c) {
            return ['id' => $c->id, 'company_name' => $c->name];
        });

        return response()->json(['company_list' => $company_list]);
    }

    /**
     * Return departments for a given unit (for AJAX select population).
     */
    public function unitWiseDepartment(Request $request)
    {
        $unit_id = $request->get('unit_id');
        $departments = [];
        if ($unit_id) {
            $departments = Department::where('unit_id', $unit_id)->orderBy('department_name')->get(['id', 'department_name']);
        }

        $department_list = collect($departments)->map(function ($d) {
            return ['id' => $d->id, 'department_name' => $d->department_name];
        });

        return response()->json(['department_list' => $department_list]);
    }
}
