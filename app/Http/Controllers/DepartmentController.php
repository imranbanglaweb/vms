<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Unit;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Unit::orderBy('unit_name')->get();
        return view('admin.dashboard.department.index', compact('units'));
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
                $editBtn = "<button class='btn btn-sm btn-primary editDepartment' data-id='".$row->id."' data-name='".htmlspecialchars($row->department_name, ENT_QUOTES)."' data-code='".htmlspecialchars($row->department_code, ENT_QUOTES)."' data-unit='".htmlspecialchars($row->unit_name, ENT_QUOTES)."'><i class='fa fa-edit'></i></button> ";
                $delBtn = "<button class='btn btn-sm btn-danger deleteUser' data-did='".$row->id."'><i class='fa fa-minus-circle'></i></button>";
                $btn = $editBtn . $delBtn;
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
        $validator = Validator::make($request->all(), [
            'unit_id' => 'required',
            'department_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        }

        $department = Department::updateOrCreate([
            'id' => $request->id
        ], [
            'unit_id' => $request->unit_id,
            'department_name' => $request->department_name,
            'department_code' => $request->department_code,
            'description' => $request->description,
               'created_by'     => Auth::id(),
        ]);

        return response()->json('Department saved successfully');
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
        $units = Unit::orderBy('unit_name')->get();
        return view('admin.dashboard.department.edit', compact('department','units'));
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
        $department->delete();
        if (request()->ajax()) {
            return response()->json(['ok' => true, 'message' => 'Department deleted']);
        }

        return redirect()->route('departments.index')->with('danger', 'Department deleted');
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
