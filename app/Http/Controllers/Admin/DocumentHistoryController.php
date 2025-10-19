<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentHistory;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:document-history-view');
        $this->middleware('permission:document-history-export', ['only' => ['export']]);
    }

    public function index()
    {
        $histories = DocumentHistory::with(['document', 'performer'])
            ->latest('action_date')
            ->paginate(15);
            
        $users = User::orderBy('name')->get();
        
        return view('admin.dashboard.documents.history.index', compact('histories', 'users'));
    }

    public function search(Request $request)
    {
        $query = DocumentHistory::with(['document', 'performer'])
            ->latest('action_date');

        if ($request->date_from) {
            $query->whereDate('action_date', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('action_date', '<=', $request->date_to);
        }
        
        if ($request->action) {
            $query->where('action', $request->action);
        }
        
        if ($request->user_id) {
            $query->where('performed_by', $request->user_id);
        }

        $histories = $query->paginate(15);
        
        return response()->json([
            'success' => true,
            'view' => view('admin.dashboard.documents.history.table', compact('histories'))->render()
        ]);
    }

    public function export(Request $request)
    {
        $query = DocumentHistory::with(['document', 'performer'])
            ->latest('action_date');

        // Apply filters
        if ($request->date_from) {
            $query->whereDate('action_date', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('action_date', '<=', $request->date_to);
        }
        
        if ($request->action) {
            $query->where('action', $request->action);
        }
        
        if ($request->user_id) {
            $query->where('performed_by', $request->user_id);
        }

        $histories = $query->get();
        
        switch ($request->type) {
            case 'excel':
                return $this->exportExcel($histories);
            case 'pdf':
                return $this->exportPDF($histories);
            case 'csv':
                return $this->exportCSV($histories);
            default:
                return back()->with('error', 'Invalid export type');
        }
    }

    private function exportExcel($histories)
    {
        // Excel export implementation
    }

    private function exportPDF($histories)
    {
        $pdf = PDF::loadView('admin.dashboard.documents.history.pdf', compact('histories'));
        return $pdf->download('document_history.pdf');
    }

    private function exportCSV($histories)
    {
        // CSV export implementation
    }
} 