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
                        ->whereNull('locations.deleted_at')
                        ->get();
        $units = Unit::orderBy('unit_name')->get();
        return view('admin.dashboard.location.index',compact('location_lists','units'));


    }

    /**
     * Server-side data endpoint for locations
     */
    public function data(Request $request)
    {
        $query = DB::table('locations')
            ->leftJoin('units','locations.unit_id','=','units.id')
            ->select('locations.id as id','locations.location_name','locations.address','locations.location_code','units.unit_name')
            ->whereNull('locations.deleted_at');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                // emit buttons compatible with the client-side modal-based editor
                $ln = htmlspecialchars($row->location_name ?? '', ENT_QUOTES);
                $addr = htmlspecialchars($row->address ?? '', ENT_QUOTES);
                $unit = htmlspecialchars($row->unit_name ?? '', ENT_QUOTES);
                $editBtn = "<button class=\"btn btn-sm btn-primary editLocation\" data-id=\"{$row->id}\" data-name=\"{$ln}\" data-address=\"{$addr}\" data-unit=\"{$unit}\"> <i class=\"fa fa-edit\"></i></button>";
                $deleteBtn = "<button class=\"btn btn-sm btn-danger deleteUser\" data-lid=\"{$row->id}\"><i class=\"fa fa-minus-circle\"></i></button>";
                return $editBtn . ' ' . $deleteBtn;
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
            Log::info('LocationController@store validation failed', ['payload' => $request->all(), 'errors' => $validator->errors()->all()]);
            return response()->json(['errors' => $validator->errors()->all()], 400);
        }

        Log::info('LocationController@store called', ['payload' => $request->all()]);

// return dd($request);


        // derive location_code from the selected unit's name (if available)
        $locationCode = null;
        if (!empty($request->unit_id)) {
            $unit = Unit::find($request->unit_id);
            if ($unit) {
                // set location_code to the unit's name; adjust if you prefer a slug or id-based code
                $locationCode = $unit->unit_name;
            }
        }

        $dataPayload = [
            'unit_id'        => $request->unit_id,
            'location_name'  => $request->location_name,
            'address'        => $request->address,
            'location_code'  => $locationCode,
            'status'         => 1,
            'created_by'     => Auth::id(),
        ];

        // preserve remarks/department_oder if present
        if ($request->filled('remarks')) { $dataPayload['remarks'] = $request->remarks; }
        if ($request->filled('department_oder')) { $dataPayload['department_oder'] = $request->department_oder; }

    $location = Location::updateOrCreate(['id' => $request->id], $dataPayload);
    Log::info('LocationController@store saved', ['location_id' => $location->id, 'payload' => $dataPayload]);

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
        Log::info('LocationController@destroy called', ['id' => $id, 'user_id' => Auth::id()]);
        $location = Location::find($id);
        $deleted = false;
        if ($location) {
            $location->delete();
            $deleted = true;
        }
        Log::info('LocationController@destroy result', ['id' => $id, 'deleted' => $deleted]);

        // If AJAX, return JSON so front-end can handle it
        if (request()->ajax()) {
            return response()->json(['ok' => true, 'message' => 'Location deleted successfully']);
        }

    }


}
