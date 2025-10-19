<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DocumentTypeController extends Controller
{
    public function index()
    {
        try {
            $documentTypes = DocumentType::latest()->get();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'view' => view('admin.dashboard.document_types.table', compact('documentTypes'))->render()
                ]);
            }
            
            return view('admin.dashboard.document_types.index', compact('documentTypes'));
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error loading document types: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error loading document types: ' . $e->getMessage());
        }
    }

    public function create()
    {
        if (!request()->ajax()) {
            return redirect()->route('document-types.index');
        }

        return response()->json([
            'success' => true,
            'view' => view('admin.dashboard.document_types.create_modal')->render()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:document_types',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();
            $data['created_by'] = Auth::id();
            
            DocumentType::create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Document Type created successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating document type: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        if (!request()->ajax()) {
            return redirect()->route('document-types.index');
        }

        $documentType = DocumentType::findOrFail($id);
        return response()->json([
            'success' => true,
            'view' => view('admin.dashboard.document_types.view_modal', compact('documentType'))->render()
        ]);
    }

    public function edit($id)
    {
        if (!request()->ajax()) {
            return redirect()->route('document-types.index');
        }

        $documentType = DocumentType::findOrFail($id);
        return response()->json([
            'success' => true,
            'view' => view('admin.dashboard.document_types.edit_modal', compact('documentType'))->render()
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:document_types,name,'.$id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $documentType = DocumentType::findOrFail($id);
            $documentType->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Document Type updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating document type: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $documentType = DocumentType::findOrFail($id);
            $documentType->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Document Type deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting document type: ' . $e->getMessage()
            ], 500);
        }
    }
} 