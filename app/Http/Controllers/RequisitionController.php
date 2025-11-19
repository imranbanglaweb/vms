<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\Department;
use App\Models\VehicleType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Mail\RequisitionStatusChangedMail;
use Illuminate\Support\Facades\Mail;
class RequisitionController extends Controller
{
    /**
     * Display a listing of requisitions.
     */
    // public function index()
    // {
    //     $requisitions = Requisition::with(['vehicle', 'driver'])
    //         ->orderBy('id', 'desc')
    //         ->get();

    //     return view('admin.dashboard.requisition.index', compact('requisitions'));
    // }
 public function index(Request $request)
    {
        $query = Requisition::with(['requestedBy', 'vehicle', 'driver']);

        // ==============================
        // Search Filters
        // ==============================
        if ($request->employee) {
            $query->whereHas('requestedBy', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->employee}%");
            });
        }

        if ($request->department) {
            $query->whereHas('requestedBy', function ($q) use ($request) {
                $q->where('department_name', 'like', "%{$request->department}%");
            });
        }

        if ($request->vehicle) {
            $query->whereHas('vehicle', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->vehicle}%");
            });
        }

        if ($request->date_from) {
            $query->whereDate('travel_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('travel_date', '<=', $request->date_to);
        }

        $requisitions = $query->orderBy('id', 'DESC')->paginate(10);

        // If AJAX request → return table + pagination only
        if ($request->ajax()) {
            return response()->json([
                'table'      => View::make('admin.dashboard.requisition.table', compact('requisitions'))->render(),
                'pagination' => $requisitions->links()->render(),
            ]);
        }

        return view('admin.dashboard.requisition.index', compact('requisitions'));
    }




// EXPORT EXCEL
    public function exportExcel()
    {
        return Excel::download(new RequisitionExport, 'requisitions.xlsx');
    }

    // EXPORT PDF
    public function exportPDF()
    {
        $requisitions = Requisition::with(['requestedBy','vehicle','driver'])->get();

        $pdf = PDF::loadView('admin.dashboard.requisition.pdf', compact('requisitions'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('requisitions.pdf');
    }

   public function create()
{

        $vehicles = Vehicle::where('status', 1)->get();
        $drivers  = Driver::where('status', 1)->get();
        $employees = Employee::all();
        $vehicleTypes = VehicleType::all();

    return view('admin.dashboard.requisition.create', [
        'action'      => route('requisitions.store'),
        'method'      => 'POST',
        'requisition' => new Requisition(),
        'vehicles'    => $vehicles,
        'employees'    => $employees,
        'drivers'     => $drivers
    ]);


 
        // return view('admin.dashboard.requisition.create', compact('employees', 'vehicleTypes'));
}


public function validateAjax(Request $request)
{
    $validator = Validator::make($request->all(), [
        'requested_by'   => 'required|exists:employees,id',
        'vehicle_id'     => 'nullable|exists:vehicles,id',
        'from_location'  => 'required|string|max:255',
        'to_location'    => 'required|string|max:255',
        'travel_date'    => 'required|date',
        'purpose'        => 'nullable|string|max:500',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'errors' => $validator->errors()
        ], 422);
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Validation passed'
    ]);
}


  public function store(Request $request)
{

    // dd($request->vehicle_id);
    // Complete validation for ALL form fields
    $validator = Validator::make($request->all(), [
        'employee_id' => 'required|exists:employees,id',
        'vehicle_id' => 'required|exists:vehicles,id',
        'driver_id' => 'required|exists:drivers,id',    
        'requisition_date' => 'required|date', // Add this if it's in your form
        'from_location' => 'required|string|max:255',
        'to_location' => 'required|string|max:255',
        'travel_date' => 'required|date',
        // 'return_date' => 'required|date|after_or_equal:travel_date',
        'number_of_passenger' => 'required|integer|min:1', // Add this if it's in your form
        'purpose' => 'required|string|max:500', // Changed from nullable to required
        'passengers.*.employee_id' => 'required|exists:employees,id',
    ], [
        'employee_id.required' => 'Please select an employee',
        'vehicle_id.required' => 'Please select a vehicle',
        'driver_id.required' => 'Please select a driver',
        'requisition_date.required' => 'Requisition date is required',
        'from_location.required' => 'From location is required',
        'to_location.required' => 'To location is required',
        'travel_date.required' => 'Travel date is required',
        'purpose.required' => 'Purpose is required',
        'number_of_passenger.required' => 'Number of passengers is required',
        'passengers.*.employee_id.exists' => 'Selected passenger is invalid',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'validation_error',
            'errors' => $validator->errors()
        ], 422);
    }

    DB::beginTransaction();
    try {
        $requisition = Requisition::create([
            'requested_by' => $request->employee_id,
            'vehicle_id' => $request->vehicle_id ?? null,
            'driver_id' => $request->driver_id ?? null,
            'from_location' => $request->from_location,
            'to_location' => $request->to_location,
            'requisition_date' => $request->requisition_date,
            'travel_date' => $request->travel_date,
            'return_date' => $request->return_date,
            'purpose' => $request->purpose,
            'created_by' => auth()->id() ?? 1,
        ]);

        // Add passengers if any
        if (!empty($request->passengers)) {
            foreach ($request->passengers as $passenger) {
                if (!empty($passenger['employee_id'])) {
                    \App\Models\RequisitionPassenger::create([
                        'requisition_id' => $requisition->id,
                        'employee_id' => $passenger['employee_id'],
                        'created_by' => auth()->id() ?? 1,
                    ]);
                }
            }
        }

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Requisition created successfully!',
            'redirect_url' => route('requisitions.index')
        ], 200);

    } catch (\Throwable $e) {
        DB::rollBack();
        \Log::error('Requisition store error: '.$e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Server error while saving requisition: ' . $e->getMessage()
        ], 500);
    }
}

     /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $requisition = Requisition::with(['requestedBy', 'vehicle', 'driver', 'passengers.employee'])
                                  ->findOrFail($id);

        return view('admin.dashboard.requisition.show', compact('requisition'));
        
    }

    /**
     * Edit form.
     */
    public function edit($id)
    {
        $requisition = Requisition::findOrFail($id);
        $employees   = Employee::orderBy('name')->get();
        $vehicles    = Vehicle::orderBy('vehicle_name')->get();
        $drivers     = Driver::orderBy('driver_name')->get();

        return view('admin.dashboard.requisition.edit', compact(
            'requisition', 'employees', 'vehicles', 'drivers'
        ));
    }

    /**
     * Update requisition.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'from_location' => 'required',
            'to_location'   => 'required',
            'travel_date'   => 'required|date',
        ]);

        $requisition = Requisition::findOrFail($id);

        $requisition->update([
            'vehicle_id'    => $request->vehicle_id,
            'driver_id'     => $request->driver_id,
            'from_location' => $request->from_location,
            'to_location'   => $request->to_location,
            'travel_date'   => $request->travel_date,
            'return_date'   => $request->return_date,
            'purpose'       => $request->purpose,
            'updated_by'    => Auth::id(),
        ]);

        return redirect()->route('requisitions.index')
                         ->with('success', 'Requisition updated successfully!');
    }

    /**
     * Delete requisition.
     */
    public function destroy($id)
    {
        $requisition = Requisition::findOrFail($id);
        $requisition->delete();

        return redirect()->route('requisitions.index')
                         ->with('success', 'Requisition deleted successfully!');
    }
 public function getEmployeeDetails($id)
    {
        $emp = Employee::find($id);

        if (!$emp) {
            return response()->json(['status' => 'error', 'message' => 'Employee not found']);
        }

        return response()->json([
            'status' => 'success',
            'employee' => [
                'department' => $emp->department_name,
                'unit'       => $emp->unit_name,
                'designation'=> $emp->designation,
            ]
        ]);
    }


public function updateWorkflow(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:1,2,3,4,5',
        'remarks' => 'nullable|string|max:1000',
    ]);

    $requisition = Requisition::findOrFail($id);

    // Role-based allowed transitions (example)
    $user = Auth::user();
    $newStatus = (int)$request->status;
    $oldStatus = (int)$requisition->status;

    // Example policy:
    // - employee cannot change status (except create)
    // - transport can move Requested(1) -> TransportReview(2) OR TransportReview(2) -> Pending/Approved? customize
    // - admin can Approve(3) or Reject(4) or Complete(5)
    if ($user->role === 'employee') {
        abort(403, 'Access Denied');
    }

    if ($user->role === 'transport') {
        // allow only transitions to 2 (review) or to 5 (completed) depending on your rules
        $allowed = [2];
        if (!in_array($newStatus, $allowed)) {
            abort(403, 'Transport role not allowed to set this status.');
        }
    }

    if ($user->role === 'admin') {
        // admin allowed any
    }

    // Update
    $requisition->update([
        'status' => $newStatus,
        'updated_by' => $user->id,
    ]);

    // Log
    WorkflowLog::create([
        'requisition_id' => $requisition->id,
        'changed_by' => $user->id,
        'old_status' => $oldStatus,
        'new_status' => $newStatus,
        'remarks' => $request->remarks,
    ]);

    // Dispatch event for email
    event(new RequisitionStatusChanged($requisition, $oldStatus, $newStatus, $request->remarks));

    return back()->with('success', 'Workflow updated.');
}
public function updateStatus(Request $request, $id)
{
    // $req = Requisition::findOrFail($id);
    // $req->update([
    //     'status' => $request->status,
    //     'updated_by' => auth()->id()
    // ]);

    // return response()->json(['success' => true]);


     $req = Requisition::findOrFail($id);

    $old = $req->status;
    $new = $request->status;

    // Update status
    $req->update([
        'status' => $new
    ]);

    // Insert log
    WorkflowLog::create([
        'requisition_id' => $req->id,
        'changed_by' => auth()->id(),
        'action' => $new == 'Approved' ? 'APPROVED' : 'REJECTED',
        'old_status' => $old,
        'new_status' => $new,
        'remarks' => $request->comment ?? null
    ]);


    // SEND EMAIL TO EMPLOYEE
Mail::to($req->requestedBy->email)
    ->send(new RequisitionStatusChangedMail($req, $newStatus, $request->comment ?? null));

// OPTIONAL: SEND EMAIL TO ADMIN  
Mail::to('admin@company.com')
    ->send(new RequisitionStatusChangedMail($req, $newStatus));

    return response()->json(['success' => true]);
}





public function transportApprove($id)
{
    $req = Requisition::findOrFail($id);

    if ($req->status != 2) {
        return response()->json(['status' => 'error', 'message' => 'Invalid workflow step']);
    }

    $req->status = 4; // Transport Office Approved
    $req->save();

    RequisitionLogHistory::create([
    'requisition_id' => $req->id,
    'user_id' => auth()->id(),
    'action' => 'Transport Approved'
]);


    return response()->json(['status' => 'success']);
}

public function transportReject($id)
{
    $req = Requisition::findOrFail($id);

    if ($req->status != 2) {
        return response()->json(['status' => 'error', 'message' => 'Invalid workflow step']);
    }

    $req->status = 5; // Transport Office Rejected
    $req->save();

    RequisitionLogHistory::create([
    'requisition_id' => $req->id,
    'user_id' => auth()->id(),
    'action' => 'Transport Rejected',
    'note' => $request->note ?? null
]);

    return response()->json(['status' => 'success']);
}


public function adminApprove($id)
{
    $req = Requisition::findOrFail($id);

    if ($req->status != 4) {
        return response()->json(['status' => 'error', 'message' => 'Invalid workflow step']);
    }

    $req->status = 6; // Final Approval
    $req->save();

    RequisitionLogHistory::create([
    'requisition_id' => $req->id,
    'user_id' => auth()->id(),
    'action' => 'Admin Final Approved'
]);


    return response()->json(['status' => 'success']);
}

public function adminReject($id)
{
    $req = Requisition::findOrFail($id);

    if ($req->status != 4) {
        return response()->json(['status' => 'error', 'message' => 'Invalid workflow step']);
    }

    $req->status = 7; // Final Rejection
    $req->save();

    RequisitionLogHistory::create([
        'requisition_id' => $req->id,
        'user_id' => auth()->id(),
        'action' => 'Admin Final Rejected',
        'note' => $request->note ?? null
    ]);


    return response()->json(['status' => 'success']);
}




  

   


}
