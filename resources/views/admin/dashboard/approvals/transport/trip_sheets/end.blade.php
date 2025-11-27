@extends('admin.dashboard.master')

@section('main_content')
<section role="main" class="content-body" style="background:#f1f4f8;">

<div class="container mt-5">

    <h3 class="fw-bold text-primary mb-4">
        <i class="fa fa-flag-checkered me-2"></i> Complete Trip
    </h3>

    {{-- Notification --}}
    <div id="notify"></div>

    {{-- Trip Summary Card --}}
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body">

            <h5 class="mb-3 text-secondary">
                <i class="fa fa-info-circle me-2"></i> Trip Summary
            </h5>

            <div class="row g-4">

                <div class="col-md-3">
                    <div class="p-3 bg-light rounded text-center">
                        <span class="fw-bold fs-5 d-block">Requisition No</span>
                        <span class="text-muted fs-6">{{ $trip->requisition->requisition_number }}</span>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="p-3 bg-light rounded text-center">
                        <span class="fw-bold fs-5 d-block">Vehicle</span>
                        <span class="text-muted fs-6">{{ $trip->vehicle->vehicle_name }}</span>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="p-3 bg-light rounded text-center">
                        <span class="fw-bold fs-5 d-block">Driver</span>
                        <span class="text-muted fs-6">{{ $trip->driver->name }}</span>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="p-3 bg-light rounded text-center">
                        <span class="fw-bold fs-5 d-block">Trip Start</span>
                        <span class="text-muted fs-6">{{ $trip->trip_start_time }}</span>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Trip End Form --}}
    <div class="card shadow border-0">
        <div class="card-body">

            <h5 class="mb-4 text-secondary">
                <i class="fa fa-road me-2"></i> Trip Completion Details
            </h5>

            <form id="finishTripForm">
                @csrf

                <div class="row g-4">

                    <div class="col-md-4">
                        <label class="form-label fw-bold fs-6">Start KM</label>
                        <input type="number" class="form-control form-control-lg" value="{{ $trip->start_km }}" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold fs-6">End KM</label>
                        <input type="number" name="end_km" id="end_km" class="form-control form-control-lg" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold fs-6">Total KM</label>
                        <input type="number" id="total_km" class="form-control form-control-lg" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold fs-6">End Location</label>
                        <input type="text" name="end_location" class="form-control form-control-lg" required placeholder="Enter end location">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold fs-6">Remarks</label>
                        <textarea name="remarks" class="form-control form-control-lg" placeholder="Enter final remarks"></textarea>
                    </div>

                </div>

                <button type="button" class="btn btn-success btn-lg mt-4 w-100" onclick="submitFinishTrip()">
                    <i class="fa fa-check-circle me-2"></i> Finish Trip
                </button>

            </form>

        </div>
    </div>

</div>

{{-- JS --}}
<script>
function alertBox(type, msg) {
    let html = `
        <div class="alert alert-${type} alert-dismissible fade show mt-3">
            ${msg}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    $('#notify').html(html);
}

$('#end_km').on('input', function() {
    let start = {{ $trip->start_km }};
    let end = parseInt($(this).val());
    if (end > start) {
        $('#total_km').val(end - start);
    } else {
        $('#total_km').val('');
    }
});

function submitFinishTrip() {
    $.post(
        "{{ route('trip-sheets.finish', $trip->id) }}",
        $("#finishTripForm").serialize(),
        function(res){
            alertBox('success', res.message);
            setTimeout(() => window.location.href = "{{ route('trip-sheets.index') }}", 1500);
        }
    ).fail(function(xhr){
        let msg = xhr.responseJSON?.message || "Failed to complete trip.";
        alertBox('danger', msg);
    });
}
</script>

@endsection
