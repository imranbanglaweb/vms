<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\Menu;
use App\Models\Contact;
use App\Models\Document;
Use \Carbon\Carbon;
use DB;
use Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
         $user = Auth::user();

        $transportPending = Requisition::where('status', 2)->count();
        $transportApproved = Requisition::where('status', 4)->count();
        $transportRejected = Requisition::where('status', 5)->count();

        $adminPending = Requisition::where('status', 4)->count();
        $adminApproved = Requisition::where('status', 6)->count();
        $adminRejected = Requisition::where('status', 7)->count();


        // Dashboard counters
      $chartData = [
            'Pending' => Requisition::where('status', 'Pending')->count(),
            'Approved' => Requisition::where('status', 'Approved')->count(),
            'Dept_Approved' => Requisition::where('status','Dept_Approved')->count(),
            'Transport_Approved' => Requisition::where('status','Transport_Approved')->count(),
            'GM_Approved' => Requisition::where('status','GM_Approved')->count(),
            'Rejected' => Requisition::where('status','Rejected')->count(),
            'Completed' => Requisition::where('status','Completed')->count(),
        ];

        $pendingRequisitions = Requisition::where('status', 'Pending')->latest()->take(5)->get();
        $recentRequisitions = Requisition::latest()->take(10)->get();

        // Role-based requisition visibility
        if ($user->role === 'employee') {
            $requisitions = Requisition::where('requested_by', $user->id)->latest()->get();
        }
        elseif ($user->role === 'transport') {
            $requisitions = Requisition::where('status', 1)->latest()->get(); // waiting transport
        }
        else { // admin
            $requisitions = Requisition::where('status', 2)->latest()->get(); // waiting admin approval
        }

        // return view('admin.dashboard.dashboard', compact('stats', 'requisitions', 'user'));
         return view('admin.dashboard.dashboard', 
         compact(
        'chartData', 
         'pendingRequisitions', 
         'recentRequisitions',
         'transportPending',
         'transportApproved',
         'transportRejected',
          'adminPending', 'adminApproved', 'adminRejected'
         )
        );
    } 


    

   
}
