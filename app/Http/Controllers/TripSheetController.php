<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use App\Models\TripSheet;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Http\Request;

class TripSheetController extends Controller
{
    public function index()
    {
        $trips = TripSheet::with('requisition', 'vehicle', 'driver')->latest()->get();
        dd($trips);
        return view('admin.dashboard.approvals.transport.trip_sheets.index', compact('trips'));
    }

    public function show($id)
    {
        $trip = TripSheet::with('requisition', 'vehicle', 'driver')->findOrFail($id);
        return view('admin.dashboard.transport.trip_sheets.show', compact('trip'));
    }

    public function startTrip($reqId)
    {
        $requisition = Requisition::findOrFail($reqId);

        $trip = TripSheet::create([
            'requisition_id' => $requisition->id,
            'vehicle_id' => $requisition->assigned_vehicle_id,
            'driver_id' => $requisition->assigned_driver_id,
            'trip_start_time' => now(),
            'start_location' => $requisition->pickup_location,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Trip started successfully.',
            'trip_id' => $trip->id
        ]);
    }

    public function finishTrip(Request $request, $tripId)
    {
        $trip = TripSheet::findOrFail($tripId);

        $trip->update([
            'trip_end_time' => now(),
            'end_location' => $request->end_location,
            'end_km' => $request->end_km,
            'total_km' => $request->end_km - $trip->start_km,
            'remarks' => $request->remarks,
            'status' => 'finished'
        ]);

        // Update vehicle availability
        Vehicle::find($trip->vehicle_id)->update([
            'availability_status' => 'available'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Trip completed successfully.'
        ]);
    }
}

