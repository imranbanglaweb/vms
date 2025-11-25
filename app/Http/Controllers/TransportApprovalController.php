<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Http\Request;

class TransportApprovalController extends Controller
{
    /**
     * List all department-approved requisitions.
     */
    public function index()
    {
        $requisitions = Requisition::with(['requestedBy', 'department'])
            ->where('department_status', 'Approved')
            ->where('transport_status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.dashboard.approvals.transport.index', compact('requisitions'));
    }

    /**
     * Show approval screen.
     */
    public function show($id)
    {
        $requisition = Requisition::with(['requestedBy', 'department', 'unit'])
            ->findOrFail($id);

        $vehicles = Vehicle::where('status', '1')->get();
        $drivers = Driver::where('status', '1')->get();

        return view('admin.dashboard.approvals.transport.show', compact('requisition', 'vehicles', 'drivers'));
    }

    /**
     * Assign vehicle and driver.
     */
    public function assignVehicleDriver(Request $request, $id)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $req = Requisition::findOrFail($id);

        $req->assigned_vehicle_id = $request->vehicle_id;
        $req->assigned_driver_id = $request->driver_id;
        $req->transport_status = 'Assigned';
        $req->save();

        // TODO: Send notification to employee
        // event(new RequisitionAssigned($req));

        return response()->json([
            'status' => 'success',
            'message' => 'Vehicle & Driver assigned successfully!'
        ]);
    }

    /**
     * Approve final transport.
     */
    public function approve(Request $request, $id)
    {
        $req = Requisition::findOrFail($id);

        if (!$req->assigned_vehicle_id || !$req->assigned_driver_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please assign vehicle & driver first.'
            ], 422);
        }

        $req->transport_status = 'Approved';
        $req->transport_remarks = $request->remarks;
        $req->transport_approved_at = now();
        $req->save();

        // TODO: Notify employee
        // event(new RequisitionTransportApproved($req));

        return response()->json([
            'status' => 'success',
            'message' => 'Transport approved successfully!'
        ]);
    }

    /**
     * Reject transport.
     */
    public function reject(Request $request, $id)
    {
        $request->validate(['remarks' => 'required']);

        $req = Requisition::findOrFail($id);

        $req->transport_status = 'Rejected';
        $req->transport_remarks = $request->remarks;
        $req->save();

        // TODO: Notify employee
        // event(new RequisitionTransportRejected($req));

        return response()->json([
            'status' => 'success',
            'message' => 'Requisition rejected!'
        ]);
    }
}
