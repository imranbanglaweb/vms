@extends('admin.dashboard.master')

@section('main_content')
<style>
    /* Readability & Professional UI */
    body { background-color: #f4f6f8; }
    .section-container { background: #fff; border-radius: 12px; padding: 28px; box-shadow: 0 6px 20px rgba(0,0,0,0.06); }
    .page-title { font-size: 15px; font-weight: 700; color: #1f2937; }
    .card-header-custom { background:#111827; color:#fff; padding: 14px 20px; border-radius: 10px; margin-bottom: 18px; }
    .form-label { font-size: 15px; font-weight: 600; color: #111827; }
    .form-control-lg, .form-select-lg, .select2-container .select2-selection--single { font-size: 15px; padding: 0.75rem 0.9rem; border-radius: 8px; }
    .btn-lg { font-size: 15px; font-weight: 600; }
    .alert { font-size: 15px; border-radius: 10px; }
    .section-title { font-size: 15px color:#000; font-weight:700; margin-bottom:10px; }
    .muted-small { font-size: 15px; color:#6b7280; }
    .row.g-4 > [class*="col-"] { margin-bottom: 0; }
    /* select2 bigger */
    .select2-container--default .select2-selection--single { height: calc(15px + 15px); }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 15px; padding-left: 0.4rem; }
</style>

<section role="main" class="content-body">
<div class="container py-4">
    <div class="section-container">
<br>
<br>
<br>
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="page-title mb-0"><i class="fa fa-clipboard-list text-primary me-2"></i>
                    {{ isset($requisition) && $requisition->id ? 'Edit Requisition' : 'Create New Requisition' }}
                </h2>
                <div class="muted-small mt-1">Complete the form below to request a vehicle</div>
            </div>

            <div>
                <a href="{{ route('requisitions.index') }}" class="btn btn-outline-dark btn-lg">
                    <i class="fa fa-arrow-left me-2"></i> Back to List
                </a>
            </div>
        </div>

        {{-- Card / Form --}}
        <div class="card-body p-0">
            <div class="card-header-custom d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <i class="fa fa-car-side text-primary me-3 fs-4"></i>
                    <div>
                        <div class="section-title mb-0">Requisition Information</div>
                        <div class="muted-small">Required fields are marked. Use the dropdowns to search options quickly.</div>
                    </div>
                </div>

                <div class="text-end">
                    @if(isset($requisition) && $requisition->id)
                        <span class="badge bg-info text-dark">#{{ $requisition->id }}</span>
                    @endif
                </div>
            </div>

            <div id="alertContainer" class="mb-3"></div>

            <form id="requisitionForm" action="{{ $action }}" method="POST" autocomplete="off">
                @csrf
                @if(isset($method) && $method === 'PUT')
                    @method('PUT')
                @endif

                <div class="row g-4">

                    {{-- Unit --}}
                    <div class="col-md-4">
                        <label class="form-label"><i class="fa fa-sitemap text-primary me-1"></i> Unit</label>
                        <br>
                        <select name="unit_id" id="unit_id" class="form-select form-select-lg select2" data-placeholder="Select unit">
                            <option value=""></option>
                            @foreach($units ?? [] as $id => $title)
                                <option value="{{ $id }}" {{ old('unit_id', $requisition->unit_id ?? '') == $id ? 'selected' : '' }}>
                                    {{ $title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Department --}}
                    <div class="col-md-4">
                        <label class="form-label"><i class="fa fa-building text-primary me-1"></i> Department</label>
                        <br>
                        <select name="department_id" id="department_id" class="form-select form-select-lg select2" data-placeholder="Select department">
                            <option value=""></option>
                            @foreach($departments ?? [] as $id => $title)
                                <option value="{{ $id }}" {{ old('department_id', $requisition->department_id ?? '') == $id ? 'selected' : '' }}>
                                    {{ $title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Employee (Requested By) --}}
                    <div class="col-md-4">
                        <label class="form-label"><i class="fa fa-user text-primary me-1"></i> Requested By (Employee)</label>
                        <select name="requested_by" id="requested_by" class="form-select form-select-lg select2" data-placeholder="Select employee" required>
                            <option value=""></option>
                            @foreach($employees ?? [] as $id => $name)
                                <option value="{{ $id }}" {{ old('requested_by', $requisition->requested_by ?? '') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Office Location --}}
                    <div class="col-md-6">
                        <label class="form-label"><i class="fa fa-map-marker-alt text-primary me-1"></i> Office Location</label>
                        <select name="office_location" id="office_location" class="form-select form-select-lg select2" data-placeholder="Select office/branch">
                            <option value=""></option>
                            @foreach($locations ?? [] as $id => $title)
                                <option value="{{ $id }}" {{ old('office_location', $requisition->office_location ?? '') == $id ? 'selected' : '' }}>
                                    {{ $title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- From Location --}}
                    <div class="col-md-6">
                        <label class="form-label"><i class="fa fa-location-arrow text-primary me-1"></i> From Location</label>
                        <input type="text" name="from_location" id="from_location" class="form-control form-control-lg border-secondary" placeholder="Pickup / starting point" value="{{ old('from_location', $requisition->from_location ?? '') }}" required>
                    </div>

                    {{-- To Location --}}
                    <div class="col-md-6">
                        <label class="form-label"><i class="fa fa-map-pin text-primary me-1"></i> To Location</label>
                        <input type="text" name="to_location" id="to_location" class="form-control form-control-lg border-secondary" placeholder="Destination" value="{{ old('to_location', $requisition->to_location ?? '') }}" required>
                    </div>

                    {{-- Vehicle --}}
                    <div class="col-md-6">
                        <label class="form-label"><i class="fa fa-car text-primary me-1"></i> Vehicle</label>
                        <select name="vehicle_id" id="vehicle_id" class="form-select form-select-lg select2" data-placeholder="Select vehicle">
                            <option value=""></option>
                            @foreach($vehicles ?? [] as $id => $name)
                                <option value="{{ $id }}" {{ old('vehicle_id', $requisition->vehicle_id ?? '') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Driver --}}
                    <div class="col-md-6">
                        <label class="form-label"><i class="fa fa-user-tie text-primary me-1"></i> Driver</label>
                        <select name="driver_id" id="driver_id" class="form-select form-select-lg select2" data-placeholder="Select driver">
                            <option value=""></option>
                            @foreach($drivers ?? [] as $id => $name)
                                <option value="{{ $id }}" {{ old('driver_id', $requisition->driver_id ?? '') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Travel Date --}}
                    <div class="col-md-6">
                        <label class="form-label"><i class="fa fa-calendar-day text-primary me-1"></i> Travel Date & Time</label>
                        <input type="text" name="travel_date" id="travel_date" class="form-control form-control-lg flatpickr" placeholder="Select travel date & time" value="{{ old('travel_date', isset($requisition->travel_date) ? $requisition->travel_date : '') }}" required>
                    </div>

                    {{-- Return Date --}}
                    <div class="col-md-6">
                        <label class="form-label"><i class="fa fa-calendar-check text-primary me-1"></i> Return Date & Time</label>
                        <input type="text" name="return_date" id="return_date" class="form-control form-control-lg flatpickr" placeholder="Select return date & time" value="{{ old('return_date', isset($requisition->return_date) ? $requisition->return_date : '') }}">
                    </div>

                    {{-- Purpose --}}
                    <div class="col-md-12">
                        <label class="form-label"><i class="fa fa-align-left text-primary me-1"></i> Purpose</label>
                        <textarea name="purpose" id="purpose" rows="3" class="form-control form-control-lg border-secondary" placeholder="Purpose / notes...">{{ old('purpose', $requisition->purpose ?? '') }}</textarea>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label class="form-label"><i class="fa fa-info-circle text-primary me-1"></i> Status</label>
                        <select name="status" class="form-select form-select-lg border-secondary">
                            @php $cur = old('status', $requisition->status ?? 'Pending'); @endphp
                            <option value="Pending" {{ $cur == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ $cur == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ $cur == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Completed" {{ $cur == 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                </div> {{-- /.row --}}

                {{-- Buttons --}}
                <div class="text-end mt-5">
                    <button type="submit" class="btn btn-success btn-lg px-4 me-2">
                        <i class="fa fa-paper-plane me-2"></i> {{ isset($requisition) && $requisition->id ? 'Update Requisition' : 'Submit Requisition' }}
                    </button>
                    <a href="{{ route('requisitions.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                        <i class="fa fa-times me-2"></i> Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>
</section>

{{-- Dependencies: jQuery, Select2, Flatpickr (CDN used here) --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 for all select2 elements
    $('.select2').select2({
        width: '100%',
        theme: 'classic',
        allowClear: true,
        placeholder: function(){ return $(this).data('placeholder') || 'Select'; }
    });

    // Flatpickr datetime with time enabled
    $('.flatpickr').flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        altInput: true,
        altFormat: "F j, Y (H:i)",
        allowInput: true
    });

    // If server provided ISO datetimes for existing record, populate flatpickr (handles edit)
    @if(old('travel_date', $requisition->travel_date ?? false))
        $('#travel_date').flatpickr().setDate("{{ old('travel_date', $requisition->travel_date) }}", true);
    @endif
    @if(old('return_date', $requisition->return_date ?? false))
        $('#return_date').flatpickr().setDate("{{ old('return_date', $requisition->return_date) }}", true);
    @endif

    // AJAX submit (works for create & update)
    $('#requisitionForm').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const url = $form.attr('action');
        $('#alertContainer').html('');
        $('#loader').removeClass('d-none');

        $.ajax({
            url: url,
            type: $form.find('input[name="_method"]').val() || $form.attr('method') || 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                $('#loader').addClass('d-none');
                let message = response.message || 'Requisition saved successfully!';
                $('#alertContainer').html(`
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-2"></i> ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
                // on create reset form, on edit we might leave data — choose to redirect to index
                setTimeout(function(){
                    window.location.href = "{{ route('requisitions.index') }}";
                }, 1400);
            },
            error: function(xhr) {
                $('#loader').addClass('d-none');
                let errors = '';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function(k, v) {
                        errors += `<li>${v[0]}</li>`;
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errors = `<li>${xhr.responseJSON.message}</li>`;
                } else {
                    errors = '<li>Something went wrong. Please try again.</li>';
                }
                $('#alertContainer').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-exclamation-circle me-2"></i>Error:</strong>
                        <ul class="mt-2 mb-0">${errors}</ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
            }
        });
    });
});
</script>
@endsection
