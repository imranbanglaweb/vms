<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Illuminate\Http\Request;

class RequisitionApprovalController extends Controller
{
    public function approve(Request $request, $id)
    {
        $req = Requisition::findOrFail($id);
        $user = auth()->user();

        switch ($user->role) {

            case 'dept_head':
                if ($req->status != 'Pending') return response()->json(["error" => "Already processed"]);
                $req->status = 'Dept_Approved';
                break;

            case 'transport_admin':
                if ($req->status != 'Dept_Approved') return response()->json(["error" => "Not department-approved"]);
                $req->status = 'Transport_Approved';
                break;

            case 'gm':
                if ($req->status != 'Transport_Approved') return response()->json(["error" => "Not transport-approved"]);
                $req->status = 'GM_Approved';
                break;

            case 'super_admin':
                $req->status = 'Completed';
                break;

            default:
                return response()->json(["error" => "You are not allowed to approve"]);
        }

        $req->save();

        return response()->json(["success" => true]);
    }



    public function reject(Request $request, $id)
    {
        $req = Requisition::findOrFail($id);
        $req->status = 'Rejected';
        $req->save();

        return response()->json(["success" => true]);
    }
}

