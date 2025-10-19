<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Page;
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

class MenuController extends Controller
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
        
          $menus = Menu::orderBy('menu_oder','ASC')->paginate(100);
        return view('admin.dashboard.menus.index',compact('menus'))
            ->with('i', ($request->input('page', 1) - 1) * 5);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         // $menus = Menu::get();
        $menus = Menu::orderBy('id','DESC')->get();
    $permission_lists = Permission::orderBy('id','ASC')->get();
        return view('admin.dashboard.menus.create',compact('menus','permission_lists'));
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
                    "menu_name" => "required",
                    "menu_type" => "required",
                    "menu_permission" => "required",
        ]);

        if ($validator->fails()) {
              return redirect()->back()->withErrors($validator->errors());
            // return response()->json(['errors' => $validator->errors()->all()], 400);
        }
        try {

            DB::beginTransaction();
            $user = Auth::user();
            $menu_name = $request->menu_name;
           
            if (!empty($menu_name)) {
            // dd($request);

            // Menu entry

            $menu = new Menu();
            $menu->menu_name     = $request->menu_name;
            $menu->menu_parent     = $request->menu_parent;
            $menu->menu_slug     = \Str::slug($request->menu_name);
            $menu->menu_type     =  $request->menu_type;
            $menu->menu_location =  $request->menu_location;
            $menu->menu_icon     =  $request->menu_icon;
            $menu->menu_url     =  $request->menu_url;
            $menu->menu_permission     =  $request->menu_permission;
            $menu->created_by    = $user->id;
            $menu->save();

            }
           
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollback();
            return response()->json(['errors' => array($exception->getMessage().__('voyager::generic.try_again'))], 422);
        }
   
        return redirect()->route('menus.index')
                        ->with('success','Menu Added Successfully');
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
    public function edit($id)
    {
   $pages = Page::orderBy('id','ASC')->get();
   $permission_lists = Permission::orderBy('id','ASC')->get();
        $menu_edit = DB::table('menus')->where('id',$id)->first();
        $menus = Menu::get();

             return view('admin.dashboard.menus.edit',compact('menu_edit','menus','permission_lists','pages'));
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
        

         $validator = Validator::make($request->all(), [
                    "menu_name" => "required",
                    "menu_type" => "required",
                    "menu_permission" => "required",
        ]);

        if ($validator->fails()) {
              return redirect()->back()->withErrors($validator->errors());
            // return response()->json(['errors' => $validator->errors()->all()], 400);
        }
        try {

            DB::beginTransaction();
            $user = Auth::user();
            $menu_name = $request->menu_name;
           
            if (!empty($menu_name)) {
            // dd($request);

            // Menu entry

            $menu = Menu::find($id);
            $menu->menu_name     = $request->menu_name;
            $menu->menu_parent     = $request->menu_parent;
            $menu->menu_slug     = \Str::slug($request->menu_name);
            $menu->menu_type     =  $request->menu_type;
            $menu->menu_permission =  $request->menu_permission;
            $menu->menu_location =  $request->menu_location;
            $menu->menu_icon     =  $request->menu_icon;
            $menu->menu_url     =  $request->menu_url;
            $menu->created_by    = $user->id;
            $menu->save();

            }
           
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollback();
            return response()->json(['errors' => array($exception->getMessage().__('voyager::generic.try_again'))], 422);
        }
   
        return redirect()->route('menus.index')
                        ->with('success','Menu Updated Successfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {

        $menu_id = $request->menu_id;
// return dd($menu_id);
         Menu::find($menu_id)->delete();
        return redirect()->route('menus.index')
                        ->with('danger','Menu Deleted successfully');
    }



     public function menuoder(Request $request)
    {
        \Log::info('Menu reorder request:', $request->all());
        
        try {
            $itemID = $request->itemID;
            $itemIndex = $request->itemIndex;

            \Log::info('Updating menu order:', [
                'itemID' => $itemID,
                'itemIndex' => $itemIndex
            ]);

            // Update the current menu item's order
            $affected = DB::table('menus')
                ->where('id', $itemID)
                ->update(['menu_oder' => $itemIndex]);

            // Reorder other menu items
            $menus = DB::table('menus')
                ->where('id', '!=', $itemID)
                ->orderBy('menu_oder', 'ASC')
                ->get();

            $order = 1;
            foreach ($menus as $menu) {
                if ($order == $itemIndex) {
                    $order++;
                }
                DB::table('menus')
                    ->where('id', $menu->id)
                    ->update(['menu_oder' => $order]);
                $order++;
            }

            \Log::info('Menu order updated successfully');
            
            return response()->json([
                'success' => true,
                'message' => 'Menu order updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating menu order: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating menu order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reorder(Request $request)
    {
        try {
            $order = $request->input('order', []);
            $this->updateMenuOrder($order);
            
            return response()->json([
                'success' => true,
                'message' => 'Menu order updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating menu order: ' . $e->getMessage()
            ], 500);
        }
    }

    private function updateMenuOrder($items, $parentId = null)
    {
        foreach ($items as $index => $item) {
            $menu = Menu::findOrFail($item['id']);
            $menu->update([
                'menu_parent' => $parentId,
                'menu_oder' => $index + 1
            ]);

            if (isset($item['children'])) {
                $this->updateMenuOrder($item['children'], $menu->id);
            }
        }
    }
}
