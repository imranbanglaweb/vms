<?php

namespace App\Http\Controllers;

use App\Models\IssueregisterLog;
use App\Models\IssueRegister;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
class IssueregisterLogController extends Controller
{
   

   public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function index(Request $request)
    {
        
            // $departments = Department::orderBy('id','ASC')->get();
            // $project_lists = DB::table('projects')
            //             ->select('projects.id as p_id','projects.project_name','projects.project_description','companies.company_name','units.unit_name')
            //             ->leftJoin('units','projects.unit_id','=','units.id')
            //             ->leftJoin('companies','projects.company_id','=','companies.id')
            //             ->get();


// return dd($request->id);
  if ($request->ajax()) {
            $data = IssueRegister::select(
                  'issue_registers.id',
                  'issue_registers.category_id',
                  'issue_registers.issue_date',
                  'issue_registers.raised_by',
                  'issue_registers.issue_type_id',
                  'issue_registers.issue_come_from',
                  'issue_registers.issue_status',
                  'issue_registers.remarks',
                  'issue_registers.status_changed_by',
                      )
             ->get();


            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" class="btn btn-primary btn-sm">Edit</a>';
                    return $btn;
                })
                  ->addColumn('category_name', function (IssueRegister $issue_registers) {
                         return $issue_registers->categoryName->category_name;
                    })
                  ->addColumn('employee_name', function (IssueRegister $issue_registers) {
                         return $issue_registers->employeeName->employee_name;
                    })
              
                  ->addColumn('changed_status', function (IssueRegister $issue_registers) {

                          $changed_status = '<a href="javascript:void(0)" data-id="'.$issue_registers->id.' " class="btn btn-primary btn-sm changed_status">'.$issue_registers->issue_status.'</a>';

                          if ( $issue_registers->issue_status== 'Completed') {

                             $changed_status = '<a href="javascript:void(0)" data-id="'.$issue_registers->id.' " data-issue_status="'.$issue_registers->issue_status.' "  data-remarks="'.$issue_registers->remarks.'"  class="btn btn-success btn-sm changed_status">'.$issue_registers->issue_status.'</a>';
                          }
                          elseif($issue_registers->issue_status == 'Pending'){

                               $changed_status = '<a href="javascript:void(0)" data-id="'.$issue_registers->id.' " data-issue_status="'.$issue_registers->issue_status.' "  data-remarks="'.$issue_registers->remarks.'"  class="btn btn-warning btn-sm changed_status" >'.$issue_registers->issue_status.'</a>';
                          }
                          else{
                                $changed_status = '<a href="javascript:void(0)" data-id="'.$issue_registers->id.' " data-remarks="'.$issue_registers->remarks.'" data-issue_status.="'.$issue_registers->issue_status.' "  class="btn btn-danger btn-sm changed_status" >'.$issue_registers->issue_status.'</a>';
                          }
                         return $changed_status;
                    })

                     ->addColumn('view', function (IssueRegister $issue_registers) {
                    $view = '<a href="'. route('issueregisterlog.show', $issue_registers->id) .'" class="btn btn-success btn-sm" data-id="'$issue_registers->id .' "><i class="fa fa-eye"></i> View</a>';
                    return $view;
                    })
                ->rawColumns(['action','changed_status','view'])
                ->make(true);
        }

        return view('admin.dashboard.support.index');
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
                    // "issue_status" => "required",
                    // "issue_come_from" => "required",
                    // "support_id" => "required",
        ]);


// return dd($request->issue_id);

        // if ($validator->fails()) {
        //       return redirect()->back()->withErrors($validator->errors());
        //     // return response()->json(['errors' => $validator->errors()->all()], 400);
        // }

           if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        }



           if (!empty($request->issue_status)) {


            $updatedata = array(
                 'issue_status'  =>$request->issue_status,   
                 'remarks'       =>$request->remarks,   
            );
// return dd($issue_status);

                DB::table('issue_registers')
                ->where('id',$request->issue_id)
                ->update($updatedata);

                $issue_status = IssueregisterLog::updateOrCreate(

        ['id'   => $request->id],
        [
        'issue_id'            => $request->issue_id,
        'support_status'      => $request->issue_status,
        'status_changed_by'   => Auth::id(),
        'changed_date'        => Carbon::now(),
        'oder'                => 1,
        // 'remarks'             => $request->remarks,
        'created_by'          => Auth::id(),
        ],
     
        );
        
        }
        else{
               $issue_status = IssueregisterLog::updateOrCreate(

        ['id'   => $request->id],
        [
        'issue_id'            => $request->issue_id,
        'support_status'      => $request->issue_status,
        'status_changed_by'   => Auth::id(),
        'changed_date'        => Carbon::now(),
        'oder'                => 1,
        // 'remarks'             => $request->remarks,
        'created_by'          => Auth::id(),
        ],
     
        );
        }

        // $setting->path = '/storage/'.$path;
        return response()->json('Status Updated Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\IssueregisterLog  $issueregisterLog
     * @return \Illuminate\Http\Response
     */
    public function show(IssueregisterLog $issueregisterLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\IssueregisterLog  $issueregisterLog
     * @return \Illuminate\Http\Response
     */
    public function edit(IssueregisterLog $issueregisterLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\IssueregisterLog  $issueregisterLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IssueregisterLog $issueregisterLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\IssueregisterLog  $issueregisterLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(IssueregisterLog $issueregisterLog)
    {
        //
    }
}
