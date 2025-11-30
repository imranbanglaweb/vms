@extends('admin.dashboard.master')

@section('main_content')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
/* ==== PREMIUM UI STYLE ==== */

.card-premium {
    background: #ffffff;
    border-radius: 14px;
    border: none;
    padding: 25px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
}

.input-icon {
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

.form-control-premium, .form-select {
    border-radius: 10px;
    padding: 12px 14px;
    padding-right: 40px;
    font-size: 1.6rem;
}

.form-control-premium:focus, .form-select:focus {
    box-shadow: 0 0 0 3px rgba(78,115,223,0.25);
}

.label-premium {
    font-weight: 600;
    font-size: 1.5rem;
}

.select2-container .select2-selection--single {
    height: 48px !important;
    border-radius: 10px !important;
    padding: 10px;
    font-size: 1.5rem;
}

.btn-premium {
    border-radius: 8px;
    padding: 10px 18px;
    font-size: 1.5rem;
    transition: 0.2s;
}

.btn-premium:hover {
    transform: translateY(-2px);
}

.table-premium th {
    background: #f8f9fa;
    font-size: 1.5rem;
    font-weight: 600;
}
</style>
@endpush


<section role="main" class="content-body">
<div class="container mt-4">

    <h3 class="fw-bold text-primary mb-4">
        <i class="fa fa-calendar-check me-2"></i> Maintenance Schedules
    </h3>

    <div id="notification"></div>

    <div class="card-premium mb-4">
        <form id="scheduleForm">
            @csrf
            <input type="hidden" name="schedule_id" id="schedule_id">

            <div class="row g-3">

                <div class="col-md-3 position-relative">
                    <label class="label-premium">Title</label>
                    <input type="text" class="form-control-premium" name="title" id="title">
                    <i class="fa fa-file input-icon"></i>
                    <small class="text-danger d-none" id="title_error"></small>
                </div>

                <div class="col-md-3 position-relative">
                    <label class="label-premium">Vehicle</label>
                    <select class="form-select select2" name="vehicle_id" id="vehicle_id">
                        @foreach($vehicles as $v)
                            <option value="{{ $v->id }}">{{ $v->vehicle_name }}</option>
                        @endforeach
                    </select>
                    <i class="fa fa-car input-icon"></i>
                    <small class="text-danger d-none" id="vehicle_id_error"></small>
                </div>

                <div class="col-md-3 position-relative">
                    <label class="label-premium">Maintenance Type</label>
                    <select class="form-select select2" name="maintenance_type_id" id="maintenance_type_id">
                        @foreach($types as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                    <i class="fa fa-tools input-icon"></i>
                    <small class="text-danger d-none" id="maintenance_type_id_error"></small>
                </div>

                <div class="col-md-3 position-relative">
                    <label class="label-premium">Vendor (Optional)</label>
                    <select class="form-select select2" name="vendor_id" id="vendor_id">
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $v)
                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                        @endforeach
                    </select>
                    <i class="fa fa-user input-icon"></i>
                </div>
                </div>
<hr>

<div class>
                <div class="col-md-6 position-relative">
                    <label class="label-premium">Scheduled Date</label>
                    <input type="date" class="form-control-premium" name="scheduled_at" id="scheduled_at">
                    <i class="fa fa-calendar input-icon"></i>
                    <small class="text-danger d-none" id="scheduled_at_error"></small>
                </div>

                <div class="col-md-6 position-relative">
                    <label class="label-premium">Notes</label>
                    <textarea class="form-control-premium" name="notes" id="notes" rows="2" cols="15"></textarea>
                    <i class="fa fa-comment input-icon"></i>
                </div>

            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary btn-premium" id="submitBtn">
                    <i class="fa fa-save me-2"></i> Save
                </button>

                <button type="button" class="btn btn-secondary btn-premium" id="resetBtn">
                    <i class="fa fa-undo me-2"></i> Reset
                </button>
            </div>

        </form>
    </div>


    <div class="card-premium">
        <table class="table table-hover table-premium" id="schedulesTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Vehicle</th>
                    <th>Type</th>
                    <th>Vendor</th>
                    <th>Date</th>
                    <th>Notes</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($schedules as $s)
                <tr id="row-{{ $s->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $s->title }}</td>
                    <td>{{ $s->vehicle->name }}</td>
                    <td>{{ $s->type->name }}</td>
                    <td>{{ $s->vendor->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($s->scheduled_at)->format('d M Y') }}</td>
                    <td>{{ $s->notes }}</td>
                    <td>
                        <button class="btn btn-info btn-sm editBtn" data-id="{{ $s->id }}">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $s->id }}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>
</section>



@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {

    $('.select2').select2();

    function clearErrors() {
        $('small.text-danger').addClass('d-none').text('');
        $('.form-control-premium, .form-select').removeClass('is-invalid');
    }

    $('#scheduleForm').on('submit', function (e) {
        e.preventDefault();
        clearErrors();

        let id = $('#schedule_id').val();
        let url = id 
            ? `{{ url('maintenance-schedules/update') }}/${id}`
            : `{{ route('maintenance.schedules.store') }}`;

        $('#submitBtn').html('<i class="fa fa-spinner fa-spin me-2"></i> Saving...').prop('disabled', true);

        $.ajax({
            url: url,
            type: "POST",
            data: $(this).serialize(),
            success: function (res) {

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                });

                setTimeout(() => location.reload(), 1200);
            },
            error: function (xhr) {

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, val) {
                        $('#' + key).addClass('is-invalid');
                        $('#' + key + '_error').removeClass('d-none').text(val[0]);
                    });

                    Swal.fire({
                        icon: 'warning',
                        title: 'Validation Error',
                        text: 'Please correct the highlighted fields.'
                    });
                }
            },
            complete: function () {
                $('#submitBtn').html('<i class="fa fa-save me-2"></i> Save').prop('disabled', false);
            }
        });
    });

    // Edit
    $('.editBtn').on('click', function () {
        let id = $(this).data('id');

        $.get(`{{ url('maintenance-schedules/edit') }}/${id}`, function (data) {
            $('#schedule_id').val(data.id);
            $('#title').val(data.title);
            $('#vehicle_id').val(data.vehicle_id).trigger('change');
            $('#maintenance_type_id').val(data.maintenance_type_id).trigger('change');
            $('#vendor_id').val(data.vendor_id).trigger('change');
            $('#scheduled_at').val(data.scheduled_at);
            $('#notes').val(data.notes);
        });
    });

    // Delete
    $('.deleteBtn').on('click', function () {
        let id = $(this).data('id');

        Swal.fire({
            title: "Are you sure?",
            text: "You want to delete this schedule!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#e74a3b",
            cancelButtonColor: "#858796",
            confirmButtonText: "Yes, Delete"
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: `{{ url('maintenance-schedules/delete') }}/${id}`,
                    type: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function (res) {

                        $(`#row-${id}`).fadeOut();

                        Swal.fire({
                            icon: "success",
                            title: "Deleted!",
                            text: res.message,
                            timer: 1200,
                            showConfirmButton: false
                        });

                    }
                });
            }
        });
    });

    $('#resetBtn').click(function () {
        $('#scheduleForm')[0].reset();
        $('#schedule_id').val('');
        clearErrors();
    });

});
</script>
@endpush

@endsection
