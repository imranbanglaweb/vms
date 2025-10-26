<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Company;
use App\Models\Department;
use App\Models\Location;
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


class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

            // $departments = Department::orderBy('id','ASC')->get();
            $location_lists = DB::table('locations')
                        ->select('locations.id as l_id','locations.location_name','locations.address','units.unit_name')
                        ->leftJoin('units','locations.unit_id','=','units.id')
                        ->get();
        return view('admin.dashboard.location.index',compact('location_lists'));


    }

    /**
     * Server-side data endpoint for locations
     */
    public function data(Request $request)
    {
        $query = DB::table('locations')
            ->leftJoin('units','locations.unit_id','=','units.id')
            ->select('locations.id as id','locations.location_name','locations.address','units.unit_name');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $edit = route('locations.edit', $row->id);
                $btn = "<a class='btn btn-sm btn-primary' href='{$edit}'><i class='fa fa-edit'></i></a> ";
                $btn .= "<button class='btn btn-sm btn-danger deleteUser' data-lid='".$row->id."'><i class='fa fa-minus-circle'></i></button>";
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
         $units = Unit::get();
         $companies = Company::get();
        return view('admin.dashboard.location.create',compact('companies','units'));
    }

    public function unit_wise_company(Request $request){
      
      $unit_id      = $request->unit_id;

        $data['company_list'] = DB::table('companies')
                            // ->select('moujas.id as ID', 'moujas.mouja_name','projects.id')
                            ->where('unit_id',$unit_id)
                            ->get();    
        echo json_encode($data);

    }
    public function store(Request $request)
    {

          $validator = Validator::make($request->all(), [
                    "unit_id" => "required",
                    "location_name" => "required",
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


           if (!empty($request->unit_id)) {
                $location = Location::updateOrCreate(

        ['id'   => $request->id],
        [
        'unit_id'           => $request->unit_id,
        'location_name'     => $request->location_name,
        'address'           => $request->address,
        'department_oder'   => 1,
        'remarks'           => $request->remarks,
        'status'            => 1,
        'created_by'        => Auth::id(),
        ],
     
        );
        }
        else{
               $event = Location::updateOrCreate(

        ['id'   => $request->id],
        [
        'unit_id'            => $request->unit_id,
        'location_name'      => $request->location_name,
        'address'            => $request->address,
        'department_oder'    => 1,
        'remarks'            => $request->remarks,
        'status'             => 1,
        'created_by'         => Auth::id(),
        ],
     
        );
        }

        // $setting->path = '/storage/'.$path;
        return response()->json('Location Added Successfully');
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
         $companies   = Company::get();
         $location_edit = Location::find($id);

         // return dd($companies);

        return view('admin.dashboard.location.edit',compact('location_edit','units','companies'));
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
        return redirect()->route('locations.index')
                        ->with('danger','Location Deleted successfully');
    }


}
