<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IssueRegister;
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
        try {
            // Document statistics
            $data = [
                'totalDocuments' => Document::where('created_by',Auth::id())->count(),
                'pendingDocuments' => Document::where('status', 'pending')
                    ->where('created_by',Auth::id())
                    ->count(),
                'returnedDocuments' => Document::where('status', 'returned')
                    ->where('created_by',Auth::id())->count(),
                'withdrawnDocuments' => Document::where('status', 'withdrawn')
                    ->where('created_by',Auth::id())->count(),
                'pendingApprovals' => Document::where('approval_status', 'pending')->where('created_by',Auth::id())->count(),
                'approvedDocuments' => Document::where('approval_status', 'approved')->where('created_by',Auth::id())->count(),
                'rejectedDocuments' => Document::where('approval_status', 'rejected')->where('created_by',Auth::id())->count(),
            ];

            // Vehicle management statistics (for the vehicle management dashboard)
            $today = \Carbon\Carbon::today();
            $in30 = $today->copy()->addDays(30);

            $data['totalVehicles'] = \App\Models\Vehicle::count();
            // vehicles with any maintenance scheduled in next 30 days
            $data['upcomingMaintenance'] = \App\Models\VehicleMaintenence::whereBetween('maintenance_date', [$today->toDateString(), $in30->toDateString()])->count();
            // recent maintenance records (last 30 days)
            $data['recentMaintenances'] = \App\Models\VehicleMaintenence::whereBetween('maintenance_date', [$today->copy()->subDays(30)->toDateString(), $today->toDateString()])->with('vehicle')->take(6)->get();
            $data['driversCount'] = \App\Models\Driver::count();
            // vehicles currently marked inactive (status 0 or 'inactive') — try both
            $data['inactiveVehicles'] = \App\Models\Vehicle::where(function($q){ $q->where('status', 0)->orWhere('status', 'inactive'); })->count();

            // Add recent documents for all users
            $data['recentDocuments'] = Document::with(['project', 'land', 'documentType'])
                ->where('created_by',Auth::id())
                ->latest('date')
                ->take(5)
                ->get();

            // Recent pending approvals for super admin
            if (auth()->user()->hasRole('Super Admin')) {
                $data['recentPendingApprovals'] = Document::where('approval_status', 'pending')
                    ->where('created_by',Auth::id())
                    ->with(['creator', 'project', 'land', 'documentType'])
                    ->latest()
                    ->take(5)
                    ->get();
            }

   $user_type_admin = DB::table('users')->select('user_type','id')
->where('id',Auth::id())
->where('user_type','super_user')
->first();  

$user_type_department_head = DB::table('users')->select('user_type','id')
->where('id',Auth::id())
->where('user_type','department_head')
->first();

       





         $sidebar_menus = Menu::orderBy('menu_oder','ASC')->where('menu_parent',0)->get();
         
        return view('admin.dashboard.dashboard', $data);
        
        } catch (\Exception $e) {
            \Log::error('Dashboard Error: ' . $e->getMessage());
            return view('admin.dashboard.dashboard', [
                'totalDocuments' => 0,
                'pendingDocuments' => 0,
                'returnedDocuments' => 0,
                'withdrawnDocuments' => 0,
                'pendingApprovals' => 0,
                'approvedDocuments' => 0,
                'rejectedDocuments' => 0,
                'recentDocuments' => collect([]),
                'recentPendingApprovals' => collect([])
            ])->withErrors(['error' => 'Error loading dashboard data']);
        }
    } 

    public function contactlistview(Request $request)
    {

         $contact_list_views = Contact::orderBy('id','ASC')->get();
         
        return view('admin.dashboard.contactlist.contactlist',compact('contact_list_views'));
    }

    public function getRecentDocuments(Request $request)
    {
        $search = $request->get('search');
        
        $documents = Document::with(['project', 'land', 'documentType'])
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->whereHas('project', function($q) use ($search) {
                        $q->where('project_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('land', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('documentType', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('document_taker', 'like', "%{$search}%")
                    ->orWhere('witness_name', 'like', "%{$search}%")
                    ->orWhere('vault_number', 'like', "%{$search}%");
                });
            })
            ->latest('date')
            ->take(5)
            ->get();

        return view('admin.dashboard.documents.recent_documents_table', compact('documents'));
    }

    public function getPendingApprovals(Request $request)
    {
        $search = $request->get('search');
        
        $documents = Document::where('approval_status', 'pending')
            ->with(['project', 'land', 'documentType'])
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->whereHas('project', function($q) use ($search) {
                        $q->where('project_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('land', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('documentType', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('document_taker', 'like', "%{$search}%")
                    ->orWhere('witness_name', 'like', "%{$search}%")
                    ->orWhere('vault_number', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.documents.pending_approvals_table', compact('documents'));
    }
}
