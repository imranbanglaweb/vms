<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Land;
use App\Models\DocumentType;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DocumentHistory;
use App\Notifications\DocumentApproved;
use App\Notifications\DocumentRejected;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            $query = Document::with(['project', 'land', 'documentType'])
                ->latest();

            // Apply filters
            if(request('project_name')) {
                $query->whereHas('project', function($q) {
                    $q->where('project_name', 'like', '%' . request('project_name') . '%');
                });
            }
            
            if(request('land_name')) {
                $query->whereHas('land', function($q) {
                    $q->where('name', 'like', '%' . request('land_name') . '%');
                });
            }
            
            if(request('document_name')) {
                $query->whereHas('documentType', function($q) {
                    $q->where('name', 'like', '%' . request('document_name') . '%');
                });
            }
            
            if(request('status')) {
                $query->where('status', request('status'));
            }
            
            if(request('date_from')) {
                $query->whereDate('date', '>=', request('date_from'));
            }
            
            if(request('date_to')) {
                $query->whereDate('date', '<=', request('date_to'));
            }
            
            if(request('document_taker')) {
                $query->where('document_taker', 'LIKE', '%' . request('document_taker') . '%');
            }
            
            if(request('returner_name')) {
                $query->where('returner_name', 'LIKE', '%' . request('returner_name') . '%');
            }

            $documents = $query->where('created_by',Auth::id())->get();
            
            return response()->json([
                'success' => true,
                'view' => view('admin.dashboard.documents.table', compact('documents'))->render()
            ]);
        }
        
        // Initial page load
        $documents = Document::with(['project', 'land', 'documentType'])
            ->latest()
            ->where('created_by',Auth::id())
            ->paginate(10);
        $projects = Project::select('id', 'project_name')->orderBy('project_name')->get();
        $lands = Land::select('id', 'name')->orderBy('name')->get();
        $documentTypes = DocumentType::select('id', 'name')->orderBy('name')->get();
        
        return view('admin.dashboard.documents.index', compact('documents', 'projects', 'lands', 'documentTypes'));
    }

    public function create()
    {
        try {
            if(request()->ajax()) {
                $projects = Project::select('id', 'project_name')->orderBy('project_name')->get();
                $lands = Land::select('id', 'name')->orderBy('name')->get();
                $documentTypes = DocumentType::select('id', 'name')->orderBy('name')->get();
                
                $view = view('admin.dashboard.documents.create_modal', 
                    compact('projects', 'lands', 'documentTypes'))->render();
                
                return response()->json([
                    'success' => true,
                    'view' => $view
                ]);
            }
            return abort(404);
        } catch (\Exception $e) {
            \Log::error('Create Document Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading form: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'land_id' => 'required|exists:lands,id',
            'document_id' => 'required|exists:document_types,id',
            'document_taker' => 'required|string|max:255',
            // 'witness_name' => 'required|string|max:255',
            'withdrawal_reason' => 'required|string',
            'vault_number' => 'required|string|max:255',
            'vault_location' => 'required|string|max:255',
            'proposed_return_date' => 'required|date',
            'status' => 'required|in:active,inactive,withdrawn,expired,pending,checked_out,returned',
            'document_scan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048' // Optional, max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Prepare document data
            $documentData = [
                'date' => now(),
                'project_id' => $request->project_id,
                'land_id' => $request->land_id,
                'document_id' => $request->document_id,
                'document_taker' => Auth::user()->name,
                'witness_name' => $request->witness_name,
                'withdrawal_reason' => $request->withdrawal_reason,
                'vault_number' => $request->vault_number,
                'vault_location' => $request->vault_location,
                'proposed_return_date' => $request->proposed_return_date,
                'status' => $request->status,
                'approval_status' => 'pending',
                'created_by' => auth()->id()
            ];

            // Handle document scan if uploaded
            if ($request->hasFile('document_scan')) {
                $file = $request->file('document_scan');
                $fileName = time() . '_' . $file->getClientOriginalName();
                
                // Store file in storage/app/public/documents
                $path = $file->storeAs('documents', $fileName);
                  // $file->storeAs('documents', $fileName);
                
                // Add file path to document data
                $documentData['document_scan'] = $fileName;
            }

            // Create document
            $document = Document::create($documentData);

            // Create history record
            DocumentHistory::create([
                'document_id' => $document->id,
                'action' => 'created',
                'details' => 'Document created by ' . auth()->user()->name,
                'performed_by' => auth()->id(),
                'action_date' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Document created successfully!',
                'document' => $document
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Document Creation Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error creating document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Document $document)
    {
        if(request()->ajax()) {
            return response()->json([
                'success' => true,
                'view' => view('admin.dashboard.documents.show_modal', compact('document'))->render()
            ]);
        }
        
        return abort(404);
    }

    public function edit(Document $document)
    {
        if(request()->ajax()) {
            $projects = Project::select('id', 'project_name')->orderBy('project_name')->get();
            $lands = Land::select('id', 'name')->orderBy('name')->get();
            $documentTypes = DocumentType::select('id', 'name')->orderBy('name')->get();
            
            return response()->json([
                'success' => true,
                'view' => view('admin.dashboard.documents.edit_modal', 
                    compact('document', 'projects', 'lands', 'documentTypes'))->render()
            ]);
        }
        
        return abort(404);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'land_id' => 'required|exists:lands,id',
            'document_id' => 'required|exists:document_types,id',
            'document_taker' => 'required|string|max:255',
            'witness_name' => 'required|string|max:255',
            'withdrawal_reason' => 'required|string',
            'vault_number' => 'required|string|max:255',
            'vault_location' => 'required|string|max:255',
            'proposed_return_date' => 'required|date',
            // 'status' => 'required|in:active,inactive,withdrawn,expired,pending,checked_out,returned',
            // 'document_scan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:8242880' // Optional, max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Find document
            $document = Document::findOrFail($id);

            // Handle file upload if new file is provided
            if ($request->hasFile('document_scan')) {
                // Delete old file if exists
                if ($document->document_scan) {
                    Storage::delete('documents/' . $document->document_scan);
                }
                // Store new file
                $file = $request->file('document_scan');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('documents', $fileName);
                $document->document_scan = $fileName;
            }

            // Update document
            $document->update([
                'project_id' => $request->project_id,
                'land_id' => $request->land_id,
                'document_id' => $request->document_id,
                'document_taker' => Auth::user()->name,
                'witness_name' => $request->witness_name,
                'withdrawal_reason' => $request->withdrawal_reason,
                'vault_number' => $request->vault_number,
                'vault_location' => $request->vault_location,
                'proposed_return_date' => $request->proposed_return_date,
                'status' => $request->status ?? $document->status,
                'document_scan' => $document->document_scan, // Ensure the document_scan is updated
            ]);

            // Create document history
            DocumentHistory::create([
                'document_id' => $document->id,
                'action' => 'updated',
                'action_date' => now(),
                'performed_by' => auth()->id(),
                'details' => 'Document was updated'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Document updated successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Document Update Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error updating document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Document $document)
    {
        try {
            $document->delete();
            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        try {
            $documents = Document::latest()->get();
            $type = strtolower($request->type);

            switch ($type) {
                case 'excel':
                    return $this->exportExcel($documents);
                case 'pdf':
                    return $this->exportPDF($documents);
                case 'csv':
                    return $this->exportCSV($documents);
                default:
                    return response()->json(['error' => 'Invalid export type'], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Export Error: ' . $e->getMessage());
            return response()->json(['error' => 'Export failed'], 500);
        }
    }

    private function exportExcel($documents)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'ID', 'Date', 'Project Name', 'Land Name', 'Document Type',
            'Document Taker', 'Witness Name', 'Vault Number', 'Vault Location',
            'Return Date', 'Status'
        ];
        $sheet->fromArray([$headers], NULL, 'A1');

        // Set data
        $row = 2;
        foreach ($documents as $document) {
            $sheet->fromArray([[
                $document->id,
                $document->date->format('d-m-Y'),
                $document->project_name,
                $document->land_name,
                $document->document_name,
                $document->document_taker,
                $document->witness_name,
                $document->vault_number,
                $document->vault_location,
                $document->proposed_return_date->format('d-m-Y'),
                ucfirst($document->status)
            ]], NULL, "A$row");
            $row++;
        }

        // Style the header
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        foreach(range('A','K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'documents_' . date('Y-m-d') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $filename .'"');
        $writer->save('php://output');
    }

    private function exportPDF($documents)
    {
        $pdf = PDF::loadView('admin.dashboard.documents.export_pdf', compact('documents'));
        return $pdf->download('documents_' . date('Y-m-d') . '.pdf');
    }

    private function exportCSV($documents)
    {
        $filename = 'documents_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($handle, [
            'ID', 'Date', 'Project Name', 'Land Name', 'Document Type',
            'Document Taker', 'Witness Name', 'Vault Number', 'Vault Location',
            'Return Date', 'Status'
        ]);

        // Add data
        foreach ($documents as $document) {
            fputcsv($handle, [
                $document->id,
                $document->date->format('d-m-Y'),
                $document->project_name,
                $document->land_name,
                $document->document_name,
                $document->document_taker,
                $document->witness_name,
                $document->vault_number,
                $document->vault_location,
                $document->proposed_return_date->format('d-m-Y'),
                ucfirst($document->status)
            ]);
        }

        fclose($handle);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'. $filename .'"');
    }

    public function returnDocument(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'returner_name' => 'required|string|min:3|max:255',
            // 'document_id' => 'required|string|min:3|max:255',
            'document_id' => 'required|exists:document_types,id',
            // 'return_witness' => 'required|string|min:3|max:255',
            'submitter_name' => 'required|string|min:1|max:255',
            'returned_documents'=>'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            // 'return_notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $document = Document::findOrFail($id);

            // Handle file upload
            $file = $request->file('returned_documents');
            $fileName = time() . '_returned_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/documents/returned', $fileName);

            // Update document
            $document->update([
                'status' => 'returned',
                'actual_return_date' => now(),
                'returner_name' => $request->returner_name,
                'submitter_name' => $request->submitter_name,
                'return_witness' => $request->return_witness,
                'returned_documents' => $fileName,
                // 'return_notes' => $request->return_notes
            ]);

            // Create history record
            DocumentHistory::create([
                'document_id' => $document->id,
                'action' => 'returned',
                'details' => "Document returned by {$request->returner_name}. " . 
                            ($request->return_notes ? "Notes: {$request->return_notes}" : ""),
                'performed_by' => auth()->id(),
                'action_date' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Document returned successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Document Return Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error processing document return: ' . $e->getMessage()
            ], 500);
        }
    }

    public function history($id)
    {
        try {
            $histories = DocumentHistory::with('performer')
                ->where('document_id', $id)
                ->orderBy('action_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'view' => view('admin.dashboard.documents.history_modal', compact('histories'))->render()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading history: ' . $e->getMessage()
            ], 500);
        }
    }

    public function pendingApproval()
    {
        $documents = Document::where('approval_status', 'pending')
            ->latest()
            ->paginate(10);
        
        return view('admin.dashboard.documents.pending_approval', compact('documents'));
    }

    public function approve(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $document = Document::findOrFail($id);
            $document->approval_status = 'approved';
            $document->approved_at = now();
            $document->approved_by = auth()->id();
            $document->save();

            // Create document history
            DocumentHistory::create([
                'document_id' => $document->id,
                'action' => 'approved',
                'details' => 'Document was approved',
                'performed_by' => auth()->id(),
                'action_date' => now()
            ]);

            // Get the document taker's user record with detailed logging
            $documentTaker = User::where('name', $document->document_taker)->first();
            
            \Log::info('Document approval process', [
                'document_id' => $document->id,
                'document_taker_name' => $document->document_taker,
                'found_user' => $documentTaker ? true : false,
                'user_email' => $documentTaker ? $documentTaker->email : 'not found',
                'auth_user' => auth()->user()->name
            ]);

            // Send notification if we have a valid user
            if ($documentTaker && $documentTaker->email) {
                try {
                    \Log::info('Sending approval notification', [
                        'to_email' => $documentTaker->email,
                        'user_name' => $documentTaker->name
                    ]);

                    // Send notification immediately instead of queuing
                    $notification = new DocumentApproved($document);
                    $documentTaker->notify($notification);

                    \Log::info('Approval notification sent successfully');
                } catch (\Exception $e) {
                    \Log::error('Failed to send approval notification', [
                        'error' => $e->getMessage(),
                        'user_id' => $documentTaker->id,
                        'email' => $documentTaker->email
                    ]);
                }
            } else {
                \Log::warning('Cannot send notification - invalid user details', [
                    'document_taker' => $document->document_taker,
                    'user_found' => $documentTaker ? 'yes' : 'no',
                    'has_email' => $documentTaker && $documentTaker->email ? 'yes' : 'no'
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Document approved successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Document approval failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error approving document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            $document = Document::findOrFail($id);
            
            if (!auth()->user()->hasRole('super-admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to reject documents'
                ], 403);
            }

            $document->update([
                'approval_status' => 'rejected',
                'rejection_reason' => $request->rejection_reason
            ]);

            // Create history record
            DocumentHistory::create([
                'document_id' => $document->id,
                'action' => 'rejected',
                'details' => 'Document rejected: ' . $request->rejection_reason,
                'performed_by' => auth()->id(),
                'action_date' => now()
            ]);

            // Notify document creator
            $document->creator->notify(new DocumentRejected($document));

            return response()->json([
                'success' => true,
                'message' => 'Document rejected successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showReturnModal($id)
    {
        try {
            // Get document with relationships
            $document = Document::findOrFail($id);
            
            // Get related data
            $project = DB::table('projects')->where('id', $document->project_id)->first();
            $land = DB::table('lands')->where('id', $document->land_id)->first();
            $documentType = DB::table('document_types')->where('id', $document->document_id)->first();
            $document_lists = DB::table('document_types')->get();

            // Debug information
            \Log::info('Return Modal Data:', [
                'document' => $document,
                'project' => $project,
                'land' => $land,
                'documentType' => $documentType,
                'document_lists' => $document_lists,
            ]);

            return response()->json([
                'success' => true,
                'view' => view('admin.dashboard.documents.return_modal', [
                    'document' => $document,
                    'project' => $project,
                    'land' => $land,
                    'documentType' => $documentType,
                    'document_lists' => $document_lists

                ])->render()
            ]);
        } catch (\Exception $e) {
            \Log::error('Return Modal Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading return form: ' . $e->getMessage()
            ], 500);
        }
    }
}