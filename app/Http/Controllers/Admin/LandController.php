<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Land;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LandController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            $lands = Land::latest()->get();
            return response()->json([
                'success' => true,
                'data' => $lands,
                'view' => view('admin.dashboard.lands.table', compact('lands'))->render()
            ]);
        }
        
        $lands = Land::latest()->paginate(10);
        return view('admin.dashboard.lands.index', compact('lands'));
    }

    public function create()
    {
        try {
            return response()->json([
                'success' => true,
                'view' => view('admin.dashboard.lands.create_modal')->render()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading form: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:lands',
            'description' => 'nullable|string',
            'location' => 'required|string',
            'area' => 'required|string',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $land = new Land();
            $land->name = $request->name;
            $land->description = $request->description;
            $land->location = $request->location;
            $land->area = $request->area;
            $land->status = $request->status;
            $land->created_by = Auth::id();
            $land->save();

            return response()->json([
                'success' => true,
                'message' => 'Land created successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating land: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $land = Land::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $land,
                'view' => view('admin.dashboard.lands.view_modal', compact('land'))->render()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading land: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $land = Land::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $land,
                'view' => view('admin.dashboard.lands.edit_modal', compact('land'))->render()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading land: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:lands,name,' . $id,
            'description' => 'nullable|string',
            'location' => 'required|string',
            'area' => 'required|string',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $land = Land::findOrFail($id);
            $land->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Land updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating land: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $land = Land::findOrFail($id);
            $land->delete();
            return response()->json([
                'success' => true,
                'message' => 'Land deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting land: ' . $e->getMessage()
            ], 500);
        }
    }
} 