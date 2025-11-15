<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\VehicleType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class RequisitionController extends Controller
{
    /**
     * Display a listing of requisitions.
     */
    public function index()
    {
        $requisitions = Requisition::with(['vehicle', 'driver'])
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.dashboard.requisition.index', compact('requisitions'));
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
        'return_date' => 'required|date|after_or_equal:travel_date',
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
     * Show the form for editing an existing requisition.
     */
    public function edit($id)
    {
        $requisition = Requisition::findOrFail($id);
        $vehicles = Vehicle::where('status', 1)->get();
        $drivers  = Driver::where('status', 1)->get();

        return view('admin.dashboard.requisition.edit', [
            'action' => route('requisition.update', $id),
            'method' => 'PUT',
            'requisition' => $requisition,
            'vehicles' => $vehicles,
            'drivers' => $drivers
        ]);
    }

    /**
     * Update the specified requisition.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'from_location' => 'required|string|max:255',
            'to_location' => 'required|string|max:255',
            'travel_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:travel_date',
            'purpose' => 'nullable|string',
            'status' => 'required|string'
        ]);

        $validated['updated_by'] = Auth::id() ?? 1;

        $requisition = Requisition::findOrFail($id);
        $requisition->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Requisition updated successfully!',
                'data' => $requisition
            ]);
        }

        return redirect()->route('requisition.index')->with('success', 'Requisition updated successfully!');
    }

    /**
     * Remove the specified requisition.
     */
    public function destroy($id)
    {
        $requisition = Requisition::findOrFail($id);
        $requisition->delete();

        return response()->json([
            'success' => true,
            'message' => 'Requisition deleted successfully!'
        ]);
    }
}
