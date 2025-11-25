@extends('admin.dashboard.master')

@section('main_content')
<section role="main" class="content-body" style="background-color:#f8f9fa;">
<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary"><i class="fa fa-shuttle-van me-2"></i> Transport Assignment</h3>
    </div>

    {{-- Notification Area --}}
    <div id="notification" class="mb-3"></div>

    <div class="card shadow border-0">
        <div class="card-body">

            {{-- Requisition Info --}}
            <h5 class="mb-3 text-secondary"><i class="fa fa-info-circle me-2"></i> Requisition Information</h5>
            <div class="row mb-4 g-3">
                <div class="col-md-4">
                    <span class="fw-bold fs-5 text-dark">Req No:</span> 
                    <span class="fs-6 text-muted">{{ $requisition->requisition_number }}</span>
                </div>
                <div class="col-md-4">
                    <span class="fw-bold fs-5 text-dark">Requester:</span> 
                    <span class="fs-6 text-muted">{{ $requisition->requestedBy->name }}</span>
                </div>
                <div class="col-md-4">
                    <span class="fw-bold fs-5 text-dark">Department:</span> 
                    <span class="badge bg-info text-dark fs-6">{{ $requisition->department->department_name }}</span>
                </div>
                <div class="col-md-4">
                    <span class="fw-bold fs-5 text-dark">Passengers:</span> 
                    <span class="badge bg-success fs-6">
                        {{ $requisition->number_of_passenger }} 
                        @if($requisition->unit)<span class="text-dark">({{ $requisition->unit->unit_name }})</span>@endif
                    </span>
                </div>
            </div>

            <hr class="my-4">

            {{-- Assign Vehicle & Driver --}}
            <h5 class="mb-3 text-secondary"><i class="fa fa-car-side me-2"></i> Assign Vehicle & Driver</h5>
            <form id="assignForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-5">Select Vehicle</label>
                        <select name="vehicle_id" class="form-select form-select-lg">
                            <option value="">-- Choose Vehicle --</option>
                            @foreach($vehicles as $v)
                                <option value="{{ $v->id }}">{{ $v->vehicle_name }} ({{ $v->model }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-5">Select Driver</label>
                        <select name="driver_id" class="form-select form-select-lg">
                            <option value="">-- Choose Driver --</option>
                            @foreach($drivers as $d)
                                <option value="{{ $d->id }}">{{ $d->driver_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="button" onclick="submitAssign()" class="btn btn-primary btn-lg mt-4 w-100">
                    <i class="fa fa-paper-plane me-2"></i> Assign
                </button>
            </form>

            <hr class="my-5">

            {{-- Final Approval --}}
            <h5 class="mb-3 text-secondary"><i class="fa fa-check-circle me-2"></i> Final Approval</h5>
            <form id="actionForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold fs-5">Remarks</label>
                    <textarea name="remarks" class="form-control form-control-lg" placeholder="Enter remarks for approval/rejection"></textarea>
                </div>
                <div class="d-flex gap-3 flex-wrap mt-3">
                    <button type="button" class="btn btn-success btn-lg flex-fill" onclick="submitAction('approve')">
                        <i class="fa fa-thumbs-up me-2"></i> Approve
                    </button>
                    <button type="button" class="btn btn-danger btn-lg flex-fill" onclick="submitAction('reject')">
                        <i class="fa fa-thumbs-down me-2"></i> Reject
                    </button>
                    <a href="{{ route('transport.approvals.index') }}" class="btn btn-secondary btn-lg flex-fill">
                        <i class="fa fa-times me-2"></i> Cancel
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- Scripts --}}
<script>
function showNotification(type, message) {
    const html = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    $('#notification').html(html);
    setTimeout(() => { $('.alert').alert('close'); }, 5000);
}

function submitAssign() {
    $.post("{{ route('transport.approvals.assign', $requisition->id) }}", 
        $("#assignForm").serialize(),
        function(res){
            showNotification('success', res.message);
            setTimeout(() => location.reload(), 1500);
        }
    ).fail(function(xhr) {
        let msg = xhr.responseJSON?.message || 'Failed to assign vehicle/driver.';
        showNotification('danger', msg);
    });
}

function submitAction(type){
    let url = type === 'approve'
        ? "{{ route('transport.approvals.approve', $requisition->id) }}"
        : "{{ route('transport.approvals.reject', $requisition->id) }}";

    $.post(url, $("#actionForm").serialize(), function(res){
        showNotification('success', res.message);
        setTimeout(() => window.location.href = "{{ route('transport.approvals.index') }}", 1500);
    }).fail(function(xhr) {
        let msg = xhr.responseJSON?.message || 'Action failed.';
        showNotification('danger', msg);
    });
}
</script>
@endsection
