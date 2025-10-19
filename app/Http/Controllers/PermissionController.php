<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Permission;
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

class PermissionController extends Controller
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
    public function index(Request $request)
    {
        
     // return dd($table_list);
     // exit();

          $permissions = Permission::orderBy('id','DESC')->get();
        return view('admin.dashboard.permission.index',compact('permissions'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {



         $permission = Permission::get();
        return view('admin.dashboard.permission.create',compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
              $this->validate($request, [
            'key' => 'required|unique:roles,name',
            // 'key' => 'required',
        ]);

    $name = str_replace('_', ' ', $request->input('key'));
    $name = ucwords($name);

    $data = [
        'name' =>$request->input('key'),
        'key' =>$name,
        'guard_name' =>'web',
        'table_name' =>$request->input('table_name'),
    ];

        $role = Permission::create($data);
        // $role->syncPermissions($request->input('permission'));
    
        return redirect()->route('permissions.index')
                        ->with('success','Permission Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($name)
    {
             $permission_edit = DB::table('permissions')->where('name',$name)->first();
        $permission = Permission::get();
        // $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.permission_id",$id)
        //     ->pluck('role_has_permissions.permission_id')
        //     ->all();

        // return dd($permission_edit);

             return view('admin.dashboard.permission.edit',compact('permission_edit','permission','name'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
          $this->validate($request, [
            'permission' => 'required',
        ]);


        $data = array(
            'name' =>$request->input('permission'),
            'table_name' =>$request->input('table_name')
        );


        $affected = DB::table('permissions')
              ->where('name',$id)
              ->update($data);
    
        // $role->syncPermissions($request->input('permission'));
    
        return redirect()->route('permissions.index')
                        ->with('success','Permission Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         Permission::find($id)->delete();
        return redirect()->route('permissions.index')
                        ->with('danger','Permission Deleted successfully');
    }
}
