<div class="container-fluid">

    <h5>Requisition Information</h5>
    <hr>

    <p><strong>Requisition No:</strong> {{ $requisition->requisition_number }}</p>
    <p><strong>Requested By:</strong> {{ $requisition->requestedBy->name ?? 'N/A' }}</p>
    <p><strong>Department:</strong> {{ $requisition->department->department_name ?? 'N/A' }}</p>
    <p><strong>Unit:</strong> {{ $requisition->unit->unit_name ?? 'N/A' }}</p>
    <p><strong>Passengers:</strong> {{ $requisition->number_of_passenger }}</p>
    <p><strong>Created At:</strong> {{ $requisition->created_at->format('d M Y') }}</p>

    <form id="actionForm" class="mt-3">
        @csrf
        <label for="remarks">Remarks</label>
        <textarea id="remarks" name="remarks" class="form-control" placeholder="Enter remarks here..."></textarea>
    </form>

</div>
