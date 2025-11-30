<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceType;
use Illuminate\Support\Facades\Auth;
class MaintenanceTypeController extends Controller
{

    
   public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $types = MaintenanceType::latest()->get();
        return view('admin.dashboard.maintenance.types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mtype_name' => 'required|string|max:255',
        ]);

// dd(Auth::id());
        $maintenanceType = MaintenanceType::create([
            'name' => $request->mtype_name,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Maintenance type added successfully.']);
    }

    public function edit(MaintenanceType $maintenanceType)
    {
        return response()->json($maintenanceType);
    }

    public function update(Request $request, MaintenanceType $maintenanceType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $maintenanceType->update($request->all());

        return response()->json(['status' => 'success', 'message' => 'Maintenance type updated successfully.']);
    }

    public function destroy(MaintenanceType $maintenanceType)
    {
        $maintenanceType->delete();
        return response()->json(['status' => 'success', 'message' => 'Maintenance type deleted successfully.']);
    }
}
