@extends('admin.dashboard.master')

@section('main_content')
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #fff !important;
    }

    .card {
        border-radius: 10px;
        border: none;
    }

    .card-body {
        padding: 1.8rem;
    }

    label.form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.4rem;
    }

    .input-group-text {
        background-color: #eef1f5;
        border-right: 0;
    }

    .form-control, .form-select {
        border-left: 0;
        height: 42px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.15rem rgba(0, 123, 255, 0.15);
    }

    h5.section-title {
        color: #0d6efd;
        font-weight: 600;
        font-size: 1rem;
        border-left: 4px solid #0d6efd;
        padding-left: 10px;
        margin-bottom: 1rem;
        margin-top: 1.5rem;
    }

    .btn-success {
        font-weight: 600;
        border-radius: 6px;
    }

    .btn-outline-primary {
        border-radius: 6px;
    }
</style>

<section role="main" class="content-body">
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
        <h3 class="fw-bold text-primary mb-0"><i class="bi bi-person-plus-fill me-2"></i>Add New Driver</h3>
        <a class="btn btn-outline-primary btn-sm px-3" href="{{ route('drivers.index') }}">
            <i class="bi bi-arrow-left-circle"></i> Back
        </a>
    </div>

    {!! Form::open(['method'=>'POST','enctype'=>'multipart/form-data','id'=>'driver_add']) !!}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <!-- Organizational Info -->
            <h5 class="section-title"><i class="bi bi-building me-1"></i> Organizational Info</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Unit</label>
                    <select class="form-select select2" name="unit_id" id="unit_id">
                        <option value="">Select Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Department</label>
                    <select class="form-select select2" name="department_id" id="department_id">
                        <option value="">Select Department</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Employee ID</label>
                    <select class="form-select select2" name="employee_nid" id="employee_nid">
                        <option value="">Select Employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->employee_nid }}">{{ $emp->employee_nid }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Driver Details -->
            <h5 class="section-title"><i class="bi bi-person-vcard me-1"></i> Driver Details</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Driver Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" name="driver_name" id="driver_name" class="form-control" placeholder="Enter Driver Name">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">License Number</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                        <input type="text" name="license_number" class="form-control" placeholder="License Number">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">License Type</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-gear-fill"></i></span>
                        <input type="text" name="license_type" class="form-control" placeholder="e.g. Heavy / Light">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">License Issue Date</label>
                    <input type="date" name="license_issue_date" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Joining Date</label>
                    <input type="date" name="joining_date" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control">
                </div>
            </div>

            <!-- Address & Contact -->
            <h5 class="section-title"><i class="bi bi-geo-alt-fill me-1"></i> Address & Contact</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">NID</label>
                    <input type="text" name="nid" id="nid" class="form-control" placeholder="National ID">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Mobile</label>
                    <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile Number">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Present Address</label>
                    <input type="text" name="present_address" id="present_address" class="form-control" placeholder="Present Address">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Permanent Address</label>
                    <input type="text" name="permanent_address" id="permanent_address" class="form-control" placeholder="Permanent Address">
                </div>
            </div>

            <!-- Work Information -->
            <h5 class="section-title"><i class="bi bi-clock-history me-1"></i> Work Information</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Working Time Slot</label>
                    <input type="text" name="working_time_slot" class="form-control" placeholder="e.g. 9am - 6pm">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Leave Status</label>
                    <select name="leave_status" class="form-select">
                        <option value="0">Active</option>
                        <option value="1">On Leave</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Photograph</label>
                    <input type="file" name="photograph" class="form-control">
                </div>
            </div>

            <div class="text-center mt-4">
               <button type="submit" class="btn btn-success btn-lg px-4">
                    <i class="bi bi-check-circle-fill me-2"></i>Save Driver
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
</section>

<!-- Required Scripts -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


<script>
    // Initialize Select2
    $('.select2').select2();

    // Setup CSRF Token
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });

    // Unit -> Department Dropdown
    $('#unit_id').change(function () {
        let id = $(this).val();
        if (id) {
            $.get("{{ route('getDepartmentsByUnit') }}", {unit_id: id}, function (res) {
                $('#department_id').html(res);
            });
        }
    });

    // Employee auto-fill
    $('#employee_nid').change(function () {
        let nid = $(this).val();
        if (nid) {
            $.get("{{ route('getEmployeeInfo') }}", {employee_nid: nid}, function (data) {
                $('#driver_name').val(data.name);
                $('#nid').val(data.nid);
                $('#mobile').val(data.mobile);
                $('#present_address').val(data.present_address);
                $('#permanent_address').val(data.permanent_address);
            });
        }
    });

    // Remove errors on input/change
    $(document).on('input change', 'input, select', function () {
        $(this).removeClass('is-invalid');
        $(this).next('.text-danger').fadeOut(200, function () {
            $(this).remove();
        });
    });

    // ✅ AJAX Form Submit
    $(document).on('submit', '#driver_add', function (e) {
        e.preventDefault(); // Prevent default form submission
        e.stopImmediatePropagation(); // Stop other handlers (fixes double submit)

        let formData = new FormData(this);
        // alert(formData);

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.text-danger').remove();

        $.ajax({
            type: 'POST',
            url: "{{ route('drivers.store') }}",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('button[type=submit]').prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Saving...');
            },
            success: function (response) {
                $('button[type=submit]').prop('disabled', false).html('<i class="bi bi-check-circle-fill me-2"></i>Save Driver');
                Swal.fire({
                    icon: 'success',
                    title: 'Driver Added Successfully!',
                    text: 'New driver has been saved successfully.',
                    confirmButtonColor: '#3085d6'
                }).then(() => {
                    window.location.href = "{{ route('drivers.index') }}";
                });
            },
            error: function (xhr) {
                $('button[type=submit]').prop('disabled', false).html('<i class="bi bi-check-circle-fill me-2"></i>Save Driver');

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, val) {
                        let input = $('[name="' + key + '"]');
                        input.addClass('is-invalid');
                        if (input.next('.text-danger').length === 0) {
                            input.after('<div class="text-danger mt-1 small">' + val[0] + '</div>');
                        }
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error!',
                        text: 'Please correct the highlighted fields.',
                        confirmButtonColor: '#d33'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error!',
                        text: 'Something went wrong. Please try again later.',
                        confirmButtonColor: '#d33'
                    });
                }
            }
        });
    });

</script>


@endsection
