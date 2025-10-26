<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Setting;
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

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

         // $menus = Menu::orderBy('menu_oder','ASC')->paginate(100);


        $settings = DB::table('settings')->where('id',1)->first();
        return view('admin.dashboard.settings.index',compact('settings'));

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
                    "site_title" => "required",
                    // "site_description" => "required",
        ]);

        //   return dd($request->site_title);

        // if ($validator->fails()) {
        //       return redirect()->back()->withErrors($validator->errors());
        //     // return response()->json(['errors' => $validator->errors()->all()], 400);
        // }

          if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        }

        $setting = Setting::find(1);

        if ($request->file('site_logo')) {
            $imagePath = $request->file('site_logo');
       $request->validate([
          'site_logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
            $imageName = $imagePath->getClientOriginalName();

            // $path = $request->file('site_logo')->storeAs('admin_resource\assets\images', $imageName, 'public');
            // $path = public_path().'//images';
            //   $imagePath->move($path, $imageName);

 $site_logo = time().'.'.$request->site_logo->extension();  
     
  $request->site_logo->move(public_path('admin_resource\assets\images'), $site_logo);

        }

        if ($request->file('admin_logo')) {
            
            $imagePath = $request->file('admin_logo');
       $request->validate([
          'admin_logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
            $imageName = $imagePath->getClientOriginalName();

            // $path = $request->file('site_logo')->storeAs('admin_resource\assets\images', $imageName, 'public');
            // $path = public_path().'//images';
            //   $imagePath->move($path, $imageName);

 $admin_logo = time().'.'.$request->admin_logo->extension();  
     
  $request->admin_logo->move(public_path('admin_resource\assets\images'), $admin_logo);
        }

        $setting->site_title = $request->site_title;
        $setting->site_description = $request->site_description;
        $setting->site_copyright_text = $request->site_copyright_text;
        $setting->admin_title = $request->admin_title;
        $setting->admin_description = $request->admin_description;


        if (!empty($admin_logo)) {
            
             $setting->admin_logo = $admin_logo;
        }

        if (!empty($site_logo)) {
            
             $setting->site_logo = $site_logo;
        }

        $setting->created_by = Auth::id();
        // $setting->path = '/storage/'.$path;
        $setting->save();

        return response()->json('Settings Updated Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
