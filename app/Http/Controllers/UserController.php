<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Unit;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Project;
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
use Illuminate\Support\Facades\Hash;
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
use App\Imports\UsersImport;
use App\Exports\UsersExport;
// use DataTables;

    
class UserController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        $users = User::orderBy('id','DESC')->get();
        return view('admin.dashboard.users.index',compact('users'));
    }


    public function department_wise_user(Request $request){

        $department_id      = $request->department_id;

        $data['user_list'] = DB::table('users')
                            ->where('department_id',$department_id)
                            ->get();    
        echo json_encode($data);


    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $roles = Role::pluck('name','name')->all();
        $roles = Role::orderBy('id','DESC')->get();
        $unit_lists = Unit::orderBy('id','DESC')->get();
        $company_lists = Company::orderBy('id','DESC')->get();
        // $department_lists = Department::orderBy('id','DESC')->get();
        $location_lists = Location::orderBy('id','DESC')->get();
        $employee_lists = Employee::orderBy('employee_order','ASC')->get();
        return view('admin.dashboard.users.create',
            compact(
                'roles',
                'employee_lists',
            ));
    }
    

 //    public function store(Request $request)
 //    {
       
 //    $validator = Validator::make($request->all(), [
 //            'employee_id' => 'required',
 //            'user_type' => 'required',
 //            'user_name' => 'required',
 //            'email' => 'required|email|unique:users,email',
 //            'password' => 'required|same:confirm-password',
 //            'roles' => 'required',
 //            'user_image' => 'required|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
 //        ]);
 //   if ($validator->fails()) {
 //            return response()->json([
 //                'success' => false,
 //                'errors' => $validator->errors()
 //            ], 422);
 //        }

 //        $input = $request->all();
 //        $input['password'] = bcrypt($input['password']);


 // try {
 //            DB::beginTransaction();

 //            // Prepare document data
 //            $usertData = [
 //                'employee_id' => $request->employee_id,
 //                'user_type'   => $request->user_type,
 //                'user_name'   => $request->user_name,
 //                'email'       => $request->email,
 //                'password'    => $input['password'],
 //                'created_by' => auth()->id()
 //            ];

 //            // Handle document scan if uploaded
 //            if ($request->hasFile('user_image')) {
 //                $file = $request->file('user_image');
 //                $fileName = time() . '_' . $file->getClientOriginalName();
                
 //                // Store file in storage/app/public/documents
 //                $path = $file->storeAs('users', $fileName);
 //                  // $file->storeAs('documents', $fileName);
                
 //                // Add file path to document data
 //                $usertData['user_image'] = $fileName;
 //            }

 //            // Create document
 //            $document = User::create($usertData);


 //            DB::commit();

 //        return response()->json([
 //            'success' => true,
 //            'message' => 'User created successfully!'
 //        ]);

 //            // return response()->json([
 //            //     'success' => true,
 //            //     'message' => 'User created successfully!',
 //            //     'document' => $document
 //            // ]);

 //        } catch (\Exception $e) {
 //            DB::rollBack();
 //            \Log::error('User Creation Error: ' . $e->getMessage());
            
 //            return response()->json([
 //                'success' => false,
 //                'message' => 'Error creating User: ' . $e->getMessage()
 //            ], 500);
 //        }


   
 //    }
    
 public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'employee_id' => 'required',
        'user_type' => 'required',
        'user_name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|same:confirm-password',
        'roles' => 'required',
        'user_image' => 'nullable|image|mimes:jpg,jpeg,png,pdf,docx|max:8192', // 8MB
    ]);

     // dd($request->file('user_image'));

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    $input = $request->all();
    $input['password'] = bcrypt($input['password']);

    $employee_list = DB::table('employees')->where('id',$request->employee_id)->first();
    $employee_id = $employee_list->employee_id;
    $department_id = $employee_list->department_id;
    $unit_id = $employee_list->unit_id;
    $location_id = $employee_list->location_id;

    try {
        DB::beginTransaction();

        // Prepare user data
        $userData = [
            // 'employee_id' => $request->employee_id,
            // 'user_type' => $request->user_type,
            // 'user_name' => $request->user_name,
            // 'email' => $request->email,
            // 'password' => $input['password'],
            // 'created_by' => auth()->id()

            'name'             => $request->user_name,
            'user_name'        => $employee_id,
            'email'            => $request->email,
            'cell_phone'       => $request->cell_phone,
            'password'         => Hash::make($request['password']),
            'unit_id'          => $request->unit_id,
            'user_type'        => $request->user_type,
            // 'role_id'          => $request->input('roles'),
            // 'company_id'       => $request->company_id,
            'department_id'    => $department_id,
            'location_id'      => $location_id,
            'created_by'       => Auth::id(),
        ];

        // Handle file upload
        if ($request->hasFile('user_image')) {
            $file = $request->file('user_image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('users', $fileName);
            $userData['user_image'] = $fileName;
        }

        // Create user
        $user = User::create($userData);

        // Assign roles
        $user->assignRole($request->input('roles'));

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'User created successfully!'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('User Creation Error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Error creating User: ' . $e->getMessage()
        ], 500);
    }
}

 
    public function show($id)
    {
        $user = User::find($id);
        return view('admin.dashboard.users.show',compact('user'));
    } 




    public function edit($id)
    {

        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        $employee_lists = Employee::orderBy('employee_order','ASC')->get();
    
        return view('admin.dashboard.users.edit',compact(
            'user','roles',
            'userRole',
            'employee_lists'
        ));



    }
    


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
           $input['password'] = Hash::make($input['password']); 
        }
    
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }



       public function userprofile(Request $request)
    {
        $user = User::find(Auth::id());
        return view('admin.dashboard.users.user-profile',compact('user'));


        
    }
    public function profileupdate(Request $request){

        
          $validator = Validator::make($request->all(), [
                    "name" => "required",
                    // "site_description" => "required",
        ], [
            'name.required' => 'The name field is required.',
        ]);



        // if ($validator->fails()) {
        //       return redirect()->back()->withErrors($validator->errors());
        //     // return response()->json(['errors' => $validator->errors()->all()], 400);
        // }

          if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        }




        $user = User::find(Auth::id());

        if ($request->file('user_image')) {
          
       $request->validate([
          'user_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
         $imageName = time().'.'.$request->user_image->extension();  
     
  $request->user_image->move(public_path('admin_resource\assets\images'), $imageName);
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if (!empty($imageName)) {

        $user->user_image = $imageName;
          
        }
        // $setting->path = '/storage/'.$path;
        $user->save();

        return response()->json('User Profile Updated Successfully');
    }


    public function profilepasswordupdate(Request $request){


          $validator = Validator::make($request->all(), [
                    'password' => 'same:confirm-password',
                    // "site_description" => "required",
        ], [
            'password.same' => 'The password and confirm password must match.',
        ]);

             

          if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        }

        $user = User::find(Auth::id());
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json('User Password Updated Successfully');



    }
    

    public function destroy($id)
    {
      $user = User::find($id);
    if ($user) {
        // Delete the user's image if it exists
        if ($user->user_image && Storage::exists('users/' . $user->user_image)) {
            Storage::delete('users/' . $user->user_image);
        }

        $user->delete();
        return response()->json(['success' => 'User deleted successfully.']);
    }

    return response()->json(['error' => 'User not found.'], 404);
    }

    public function search(Request $request)
    {
        if($request->ajax()) {
            $query = $request->get('search');
            $users = User::where('name', 'LIKE', "%{$query}%")
                         ->orWhere('email', 'LIKE', "%{$query}%")
                         ->get();

            return view('admin.dashboard.users.partials.user_table', compact('users'))->render();
        }
    }

    public function importUser(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return redirect()->back()->with('success', 'Users imported successfully.');
    }

    public function exportUser()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

public function getData()
{
    $users = User::select(['id', 'user_name','user_type', 'name', 'email', 'user_image']);
    
    return DataTables::of($users)
        ->addIndexColumn()
        ->addColumn('action', function($row){
            $btn = '<a href="'.route('users.edit', $row->id).'" class="edit btn btn-primary btn-sm">Edit</a>';
            $btn .= ' <button type="button" onclick="confirmDelete('.$row->id.')" class="deleteUser btn btn-danger btn-sm">Delete</button>';
            return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
}


    public function validateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'user_type' => 'required|in:normal_user,super_user,admin,department_head',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric',
            'password' => 'required|min:6|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return response()->json(['success' => true], 200);
    }

    public function ajaxSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
            'phone' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return response()->json(['success' => 'User added successfully']);
    }
}