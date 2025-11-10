<?php
namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Unit;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Notification;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use \DateTime;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use App\Exports\ExportLandinventory;
Use \Carbon\Carbon;
Use Redirect;
use App\Models\Department;
use App\Models\Driver;
use App\Models\Vendor;
use App\Models\VehicleType;
use App\Models\RtaOffice;

class VehicleController extends Controller
{
 public function index(Request $request)
{
    // Check if it's an AJAX request for DataTables
    if ($request->ajax()) {
        $vehicles = Vehicle::with(['department', 'driver', 'vehicleType', 'vendor'])->latest();

        return datatables()->of($vehicles)
            ->addIndexColumn() // Adds DT_RowIndex automatically
            ->addColumn('department', function($row){
                return $row->department ? $row->department->department_name : '-';
            })
            ->addColumn('driver', function($row){
                return $row->driver ? $row->driver->driver_name : '-';
            })
            ->addColumn('status', function($row){
                return $row->status == 1 
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->rawColumns(['status']) // allow HTML in status
            ->make(true);
    }

    // If normal page request
    return view('admin.dashboard.vehicles.index');
}
     protected function dropdownData()
    {
        // Use pluck to get id => name arrays
        $departments  = Department::pluck('department_name', 'id');
        $drivers      = Driver::pluck('driver_name', 'id');
        $vendors      = Vendor::pluck('name', 'id');
        $vehicleTypes = VehicleType::pluck('name', 'id');
        // $rtaOffices   = RtaOffice::pluck('name', 'id');

        // static ownership options
        $ownerships = [
            'Owned' => 'Owned',
            'Rented' => 'Rented',
            'Leased'  => 'Leased',
        ];

        return compact('departments', 'drivers', 'vendors', 'vehicleTypes', 'ownerships');
    }

    public function create()
    {
        //   $departments = Department::pluck('department_name', 'id');
        // $drivers = Driver::pluck('driver_name', 'id');
        // // $vendors = Vendor::pluck('vendor_name', 'id');
        // $vehicleTypes = VehicleType::pluck('name', 'id');
        // // $rtaOffices = RtaOffice::pluck('name', 'id');
        // $ownerships = ['Company' => 'Company', 'Private' => 'Private', 'Leased' => 'Leased'];

        // // return view('admin.vehicles.form', compact(
        // //     'departments', 'drivers', 'vendors', 'vehicleTypes', 'rtaOffices', 'ownerships'
        // // ));
        // return view('admin.dashboard.vehicles.create', compact(
        //     'departments', 'drivers', 'vehicleTypes', 'ownerships'
        // ));


  $data = $this->dropdownData();
        // pass all dropdowns to view
        return view('admin.dashboard.vehicles.form', $data);
        
    }

     public function store(Request $request)
    {
        $request->validate([
            'vehicle_name' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'registration_date' => 'required|date',
            'license_plate' => 'required|string|unique:vehicles,license_plate',
            'alert_cell_number' => 'required|string',
            'ownership' => 'required',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'driver_id' => 'required|exists:drivers,id',
            'vendor_id' => 'required|exists:vendors,id',
            'seat_capacity' => 'required|integer|min:1',
        ]);

        $vehicle = Vehicle::create(array_merge($request->all(), [
            'created_by' => Auth::id(),
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Vehicle created successfully'
        ]);
    }

    public function edit(Vehicle $vehicle)
    {
        return view('admin.dashboard.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'vehicle_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'registration_date' => 'required|date',
            'license_plate' => 'required|string|max:50',
            'alert_cell_number' => 'required|string|max:20',
            'ownership' => 'required|string|max:100',
            'vehicle_type' => 'required|string|max:100',
            'rta_office' => 'required|string|max:100',
            'driver' => 'required|string|max:100',
            'vendor' => 'required|string|max:100',
            'seat_capacity' => 'required|integer|min:1',
        ]);

        $vehicle->update($request->all());

        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated successfully!');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted successfully!');
    }
}
