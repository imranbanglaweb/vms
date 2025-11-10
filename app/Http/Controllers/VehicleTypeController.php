<?php

namespace App\Http\Controllers;

use App\Models\VehicleType;
use Illuminate\Http\Request;
use DataTables;

class VehicleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function index(Request $request)
        {
            if ($request->ajax()) {
                $data = VehicleType::latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        return '
                            <a href="'.route('vehicle-type.edit', $row->id).'" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'"><i class="fa fa-trash"></i></button>
                        ';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return view('admin.dashboard.vehicletypes.index');
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('admin.dashboard.vehicletypes.create');
    }

 public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'status' => 'required|in:0,1',
    ]);

    VehicleType::create([
        'name' => $request->name,
        'description' => $request->description,
        'status' => $request->status,
        'created_by' => auth()->id() ?? 1,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Vehicle type created successfully!'
    ]);
}




    public function edit($id)
    {
        $vehicleType = VehicleType::findOrFail($id);
        return view('vehicle_type.edit', compact('vehicleType'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $vehicleType = VehicleType::findOrFail($id);
        $vehicleType->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('vehicle-type.index')->with('success', 'Vehicle Type updated successfully.');
    }

    public function destroy($id)
    {
        VehicleType::findOrFail($id)->delete();
        return redirect()->route('vehicle-type.index')->with('warning', 'Vehicle Type deleted successfully.');
    }

    public function show($id)
    {
        $vehicleType = VehicleType::findOrFail($id);
        return view('vehicle-type.show', compact('vehicleType'));
    }
}
