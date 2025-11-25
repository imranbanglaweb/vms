<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Illuminate\Http\Request;

class DepartmentApprovalController extends Controller
{
    /**
     * List all pending requisitions for this department.
     */
    public function index()
    {
        $requisitions = Requisition::with(['requestedBy', 'department', 'unit'])
            ->where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.dashboard.approvals.department.index', compact('requisitions'));
    }

    /**
     * Show a single requisition for review.
     */

    public function show($id) {
    $requisition = Requisition::with(['requestedBy','department','unit'])->findOrFail($id);
    return view('admin.dashboard.approvals.department.show', compact('requisition'));
}

public function approve(Request $request, $id) {
    $req = Requisition::findOrFail($id);
    $req->department_status = 'approved';
    // $req->remarks = $request->remarks;
    $req->save();
    return response()->json(['message'=>'Requisition approved successfully!']);
}

public function reject(Request $request, $id) {
    $req = Requisition::findOrFail($id);
    $req->status = 'rejected';
    $req->remarks = $request->remarks;
    $req->save();
    return response()->json(['message'=>'Requisition rejected successfully!']);
}

    // public function show($id)
    // {
    //     $requisition = Requisition::with([
    //         'requestedBy',
    //         'department',
    //         'unit',
    //     ])->findOrFail($id);


    //     return view('admin.dashboard.approvals.department.modal', compact('requisition'));

    // }

    // /**
    //  * Approve the requisition.
    //  */
    // public function approve(Request $request, $id)
    // {
    //     $requisition = Requisition::findOrFail($id);

    //     $requisition->department_status = 'Approved';
    //     $requisition->department_remarks = $request->remarks;
    //     $requisition->department_approved_at = now();
    //     $requisition->save();

    //     // TODO: Add notification (Email/SMS)
    //     // event(new RequisitionDepartmentApproved($requisition));

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Requisition approved successfully.'
    //     ]);
    // }

    // /**
    //  * Reject the requisition.
    //  */
    // public function reject(Request $request, $id)
    // {
    //     $requisition = Requisition::findOrFail($id);

    //     $request->validate([
    //         'remarks' => 'required'
    //     ]);

    //     $requisition->department_status = 'Rejected';
    //     $requisition->department_remarks = $request->remarks;
    //     $requisition->save();

    //     // TODO: Add notification
    //     // event(new RequisitionDepartmentRejected($requisition));

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Requisition rejected successfully.'
    //     ]);
    // }
}
