<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Company;
use App\Models\Department;
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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use App\Exports\ExportLandinventory;
Use \Carbon\Carbon;
Use Redirect;
Use Session;


class DepartmentController extends Controller
{
      public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {

            $departments = Department::orderBy('id','DESC')->get();
            // $departments = DB::table('departments')
            //             ->select('departments.id as d_id','departments.department_name','companies.company_name','units.unit_name')
            //             ->leftJoin('units','departments.unit_id','=','units.id')
            //             ->leftJoin('companies','departments.company_id','=','companies.id')
            //             ->get();
        return view('admin.dashboard.department.index',compact('departments'));


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
        return view('admin.dashboard.department.create',compact('departments','units'));
    }

    public function unit_wise_company(Request $request){
      
      $unit_id      = $request->unit_id;

        $data['company_list'] = DB::table('companies')
                            // ->select('moujas.id as ID', 'moujas.mouja_name','projects.id')
                            ->where('unit_id',$unit_id)
                            ->get();    
        echo json_encode($data);

    }

    public function unit_wise_department(Request $request){
      
        $unit_id      = $request->unit_id;

        $data['department_list'] = DB::table('departments')
                            // ->select('moujas.id as ID', 'moujas.mouja_name','projects.id')
                            ->where('unit_id',$unit_id)
                            ->get();   




        $data['employee_info'] = DB::table('employees')
                            // ->select('moujas.id as ID', 'moujas.mouja_name','projects.id')
                    ->select(
                            'employees.id as e_id',
                            'employees.employee_name',
                            'employees.unit_id',
                            )
                            ->leftJoin('units','employees.unit_id','=','units.id')
                            ->where('unit_id',$unit_id)
                            ->get();  

        $data['location_list'] = DB::table('locations')
                            // ->select('moujas.id as ID', 'moujas.mouja_name','projects.id')
                            ->where('unit_id',$unit_id)
                            ->get(); 

        echo json_encode($data);

    }

    public function unit_wise_location(Request $request){
      
        $unit_id      = $request->unit_id;

    $data['employee_exits'] = DB::table('assignemployees')
    ->where('employee_id',$request->employee_id)
    ->count(); 


// dd($employee_exits);

              if ($data['employee_exits'] >0) {

    $location_list_by_employee = DB::table('assignemployees')
    ->where('employee_id',$request->employee_id)
    ->pluck('project_location_id'); 

    $location_id = str_replace('', '', $location_list_by_employee[0]);
    $location_list  = json_decode($location_list_by_employee, true);
    $location_list =  array_map('intval', explode(',', $location_id));

        $data['location_list'] = DB::table('locations')
                            // ->select('moujas.id as ID', 'moujas.mouja_name','projects.id')
                            ->whereIn('unit_id',$unit_id)
                            ->whereNotIn('id',$location_list)
                            ->get();
        $data['unit_list'] = DB::table('units')
                            // ->select('moujas.id as ID', 'moujas.mouja_name','projects.id')
                            ->whereIn('id',$unit_id)
                            ->get();
        // dd($data['location_list_add']);
                            
                  } 
                  else{
                              $data['location_list'] = DB::table('locations')
                            // ->select('moujas.id as ID', 'moujas.mouja_name','projects.id')
                            ->whereIn('unit_id',$unit_id)
                            ->get();



                              $data['unit_list'] = DB::table('units')
                            // ->select('moujas.id as ID', 'moujas.mouja_name','projects.id')
                            ->whereIn('id',$unit_id)
                            ->get();
                  }   
        echo json_encode($data);

    }

    public function unit_wise_location_assigned(Request $request){
      
  $data['location_list'] = DB::table('locations')
                            // ->select('moujas.id as ID', 'moujas.mouja_name','projects.id')
                            ->where('unit_id',$request->unit_id)
                            ->get();
         echo json_encode($data);   
    }

    public function unit_wise_location_employee_assigned(Request $request){

                $data['assigned_location_list'] = DB::table('locations')
                            // ->select('moujas.id as ID', 'moujas.mouja_name','projects.id')
                            ->where('unit_id',$request->emp_unit_id)
                            ->get();
         echo json_encode($data);                   
    }

    public function department_wise_employee_assigned(Request $request){

                $data['assigned_employee_list'] = DB::table('employees')
                            // ->select('moujas.id as ID', 'moujas.mouja_name','projects.id')
                            ->where('department_id',$request->department_id)
                            ->get();
         echo json_encode($data);                   
    }

    public function store(Request $request)
    {

          $validator = Validator::make($request->all(), [
                    // "unit_id" => "required",
                    "department_name" => "required",
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


           if (!empty($request->department_name)) {
                $department = Department::updateOrCreate(

        ['id'   => $request->id],
        [
        'unit_id'      => $request->unit_id,
        'department_name'      => $request->department_name,
        'department_code'   => $request->department_code,
        'company_id'   => $request->company_id,
        'department_oder'   => $request->department_oder,
        'remarks'     => $request->remarks,
        'status'     => 1,
        'created_by' => Auth::id(),
        ],
     
        );
        
        }
        else{
               $event = Department::updateOrCreate(

        ['id'   => $request->id],
        [
        'unit_id'      => $request->unit_id,
        'department_name'      => $request->department_name,
        'department_code'   => $request->department_code,
        'company_id'   => $request->company_id,
        'department_oder'   => 1,
        'remarks'     => $request->remarks,
        'status'     => 1,
        'created_by' => Auth::id(),
        ],
     
        );
        }


        // $setting->path = '/storage/'.$path;


        return response()->json('Department Added Successfully');


        // /////////////////////////////////
        

    }


    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $units = Unit::get();
         $departments = Department::get();
         $companies = Company::get();
         $department_edit = Department::find($id);
        return view('admin.dashboard.department.edit',compact('department_edit','units','departments','companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
                       Unit::find($id)->delete();
        return redirect()->route('units.index')
                        ->with('danger','Unit Deleted successfully');
    }


}
