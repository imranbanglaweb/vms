<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Company;
use App\Models\Department;
use App\Models\Location;
use App\Models\Project;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Notification;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use \DateTime;
Use \Carbon\Carbon;
Use Redirect;
Use Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeeExport;
use App\Imports\EmployeeImport;
use App\Imports\UserImport;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

       public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        //
            $employee_lists = DB::table('employees')
                        ->select(
                            'employees.id as emp_id',
                            'employees.employee_id',
                            'employees.employee_name',
                            'employees.unit_id',
                            'employees.department_id',
                            'employees.location_id',
                            'locations.id',
                            'locations.location_name',
                            'units.id',
                            'units.unit_name',
                            'departments.department_name'
                        )
                        ->leftJoin('units','units.id','=','employees.unit_id')
                        ->leftJoin('locations','locations.id','=','employees.location_id')
                        ->leftJoin('departments','departments.id','=','employees.department_id')
                        ->get();
                        // dd($employee_lists);
        return view('admin.dashboard.employee.index',compact('employee_lists'));
    }


    public function export() 
    {

          $employee_lists = DB::table('employees')
                        ->select(
                            'employees.id as emp_id',
                            'employees.employee_id',
                            'employees.employee_name',
                            'employees.unit_id',
                            'employees.department_id',
                            'employees.location_id',
                            'locations.id',
                            'locations.location_name',
                            'units.id',
                            'units.unit_name'
                        )
                        ->leftJoin('units','units.id','=','employees.unit_id')
                        ->leftJoin('locations','locations.id','=','employees.location_id')
                        ->get();
                
        // return Excel::download(new EmployeeExport, 'employee_lists.xlsx');
        return Excel::download(new EmployeeExport($employee_lists), 'employee_lists.xlsx');
          // return back();
    }
     

    public function import() 
    {

        // dd(request()->file('file'));
        Excel::import(new EmployeeImport,request()->file('file'));
             
        return back();
    }


    // importuser
    public function importuser() 
    {
        Excel::import(new UserImport,request()->file('file'));
             
        return back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

                $units = Unit::get();
         $departments = Department::get();
        return view('admin.dashboard.employee.create',compact('departments','units'));
    }

 


    public function store(Request $request)
    {
          $validator = Validator::make($request->all(), [
                    "employee_id" => "required",
        ]);

// return dd($request);

        // if ($validator->fails()) {
        //       return redirect()->back()->withErrors($validator->errors());
        //     // return response()->json(['errors' => $validator->errors()->all()], 400);
        // }

           if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        }

// return dd($request);

           if (!empty($employee_id)) {
                $employee = Employee::updateOrCreate(

        ['id'   => $request->id],
        [
        'unit_id'         => $request->unit_id,
        'company_id'      => $request->company_id,
        'department_id'   => $request->department_id,
        'location_id'     => $request->location_id ,
        'employee_id'     => $request->employee_id ,
        'employee_name'   => $request->employee_name,
        'employee_description'   => $request->employee_description,
        'employee_email'   => $request->employee_email,
        'employee_phone'   => $request->employee_phone,
        'created_by' => Auth::id(),
        ],
     
        );
        
        }
        else{
               $employee = Employee::updateOrCreate(

        ['id'   => $request->id],
        [
        'unit_id'         => $request->unit_id,
        'company_id'      => $request->company_id,
        'department_id'   => $request->department_id,
        'location_id'     => $request->location_id ,
        'employee_id'     => $request->employee_id ,
        'employee_name'   => $request->employee_name,
        'employee_description'   => $request->employee_description,
        'employee_email'   => $request->employee_email,
        'employee_phone'   => $request->employee_phone,
        'created_by' => Auth::id(),
        ],
     
        );
        }


        // $setting->path = '/storage/'.$path;


        return response()->json('Employee Added Successfully');
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
   public function edit($id)
    {
        $units = Unit::get();
        $departments = Department::get();
        $locations = Location::get();
        $employee_edit = Employee::find($id);
        return view('admin.dashboard.employee.edit',compact('employee_edit','units','locations','departments'));
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
   public function destroy($id,Request $request)
    {

        $e_id = $request->e_id;
        // dd($e_id); 
                       Employee::find($e_id)->delete();
        return redirect()->route('employees.index')
                        ->with('danger','Employee Deleted successfully');
    }
}
