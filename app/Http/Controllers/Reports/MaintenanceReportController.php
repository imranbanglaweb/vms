<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceRequisition;
use App\Models\Vehicle;
use App\Models\MaintenanceType;
use PDF;
use Excel;

class MaintenanceReportController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.reports.maintenance.index', [
            'vehicles' => Vehicle::select('id','vehicle_name')->get(),
            'types' => MaintenanceType::select('id','name')->get()
        ]);
    }

    public function ajax(Request $request)
    {
        $query = MaintenanceRequisition::with(['vehicle','employee'])->select('maintenance_requisitions.*');

        // 🔐 Role-based filtering
        if (auth()->user()->hasRole('Manager')) {
            $query->where('department_id', auth()->user()->department_id);
        }

        // Filters
        if ($request->vehicle_id)
            $query->where('vehicle_id', $request->vehicle_id);

        if ($request->type_id)
            $query->where('maintenance_type_id', $request->type_id);

        if ($request->from_date && $request->to_date)
            $query->whereBetween('maintenance_date', [
                $request->from_date,
                $request->to_date
            ]);

        $records = $query->latest()->paginate(15);

        // return dd($records);

        return view('admin.dashboard.reports.maintenance.table', compact('records'))->render();
    }

    public function excel()
    {
        return Excel::download(
            new \App\Exports\MaintenanceExport,
            'maintenance-report.xlsx'
        );
    }

    public function pdf()
    {
        $records = MaintenanceRecord::with(['vehicle','type','vendor'])->get();
        $pdf = PDF::loadView('admin.dashboard.reports.maintenance.pdf', compact('records'));
        return $pdf->download('maintenance-report.pdf');
    }
}
