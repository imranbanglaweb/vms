<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

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


    return view('admin.dashboard.requisition.create', [
        'action'      => route('requisitions.store'),
        'method'      => 'POST',
        'requisition' => new Requisition(),
        'vehicles'    => $vehicles,
        'drivers'     => $drivers
    ]);
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_location' => 'required|string|max:255',
            'to_location' => 'required|string|max:255',
            'travel_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:travel_date',
            'purpose' => 'nullable|string',
        ]);

        $validated['requested_by'] = Auth::id() ?? 1;
        $validated['created_by'] = Auth::id() ?? 1;

        $requisition = Requisition::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Requisition created successfully!',
                'data' => $requisition
            ]);
        }

        return redirect()->route('requisition.index')->with('success', 'Requisition created successfully!');
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
