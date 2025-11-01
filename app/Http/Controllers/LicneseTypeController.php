<?php

namespace App\Http\Controllers;

use App\Models\Licnese_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;


class LicneseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // If AJAX/DataTables request, return server-side JSON
        if ($request->ajax()) {
            $query = Licnese_type::select(['id', 'type_name', 'description', 'status', 'created_at']);
            return DataTables::of($query)
                ->addColumn('actions', function ($row) {
                    $edit = '<a href="' . route('license-types.edit', $row->id) . '" class="btn btn-sm btn-primary me-1">Edit</a>';
                    $delete = "<form method='POST' action='" . route('license-types.destroy', $row->id) . "' style='display:inline'>" . csrf_field() . method_field('DELETE') . "<button type='submit' class='btn btn-sm btn-danger' onclick='return confirm(\'Delete?\')'>Delete</button></form>";
                    return $edit . $delete;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        // Non-AJAX: render view
        if (View::exists('admin.dashboard.license_types.index')) {
            return view('admin.dashboard.license_types.index');
        }

        return response()->json(Licnese_type::orderBy('type_name')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (View::exists('admin.dashboard.license_types.create')) {
            return view('admin.dashboard.license_types.create');
        }
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_name' => 'required|string|max:191|unique:licnese_types,type_name',
            'description' => 'nullable|string',
            // 'status' => 'nullable|in:Active,Inactive',
        ]);
        // ensure status default
        $data = $validated;
        // $data['status'] = $request->input('status', 'Active');
        $data['created_by'] = auth()->id() ?? 1;

        // create the record
        $type = Licnese_type::create($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'License type created', 'type' => $type], 201);
        }

        return redirect()->route('license-types.index')->with('success', 'License type created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Licnese_type  $licnese_type
     * @return \Illuminate\Http\Response
     */
    public function show(Licnese_type $licnese_type)
    {
        if (View::exists('admin.dashboard.license_types.show')) {
            return view('admin.dashboard.license_types.show', compact('licnese_type'));
        }
        return response()->json($licnese_type);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Licnese_type  $licnese_type
     * @return \Illuminate\Http\Response
     */
    public function edit(Licnese_type $licnese_type)
    {
        if (View::exists('admin.dashboard.license_types.edit')) {
            return view('admin.dashboard.license_types.edit', compact('licnese_type'));
        }
        return response()->json($licnese_type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Licnese_type  $licnese_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Licnese_type $licnese_type)
    {
        $validated = $request->validate([
            'type_name' => 'required|string|max:191|unique:licnese_types,type_name,' . $licnese_type->id,
            'description' => 'nullable|string',
            'status' => 'nullable|in:Active,Inactive',
        ]);

        $licnese_type->update($validated + ['updated_by' => auth()->id() ?? 1]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'License type updated', 'type' => $licnese_type]);
        }

        return redirect()->route('license-types.index')->with('success', 'License type updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Licnese_type  $licnese_type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Licnese_type $licnese_type)
    {
        $licnese_type->delete();
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['message' => 'License type deleted']);
        }
        return redirect()->route('license-types.index')->with('success', 'License type deleted');
    }
}
