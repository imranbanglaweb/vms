<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceVendor;

class MaintenanceVendorController extends Controller
{
    public function index()
    {
        $vendors = MaintenanceVendor::latest()->get();
        return view('admin.dashboard.maintenance.vendors.index', compact('vendors'));
    }

    public function store(Request $request)
    {

        // dd($request->vendor_name);
        $request->validate([
            'vendor_name' => 'required|string|max:255',
        ]);

            $vendor = new MaintenanceVendor();
            $vendor->name          = $request->vendor_name;
            $vendor->created_by    = auth()->id() ?? 1;
            $vendor->save();

        return response()->json(['status' => 'success', 'message' => 'Vendor added successfully.']);
    }

    public function edit(MaintenanceVendor $vendor)
    {
        return response()->json($vendor);
    }

    public function update(Request $request, MaintenanceVendor $vendor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $vendor->update($request->all());

        return response()->json(['status' => 'success', 'message' => 'Vendor updated successfully.']);
    }

    public function destroy(MaintenanceVendor $vendor)
    {
        $vendor->delete();
        return response()->json(['status' => 'success', 'message' => 'Vendor deleted successfully.']);
    }
}
