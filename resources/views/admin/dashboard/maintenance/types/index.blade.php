@extends('admin.dashboard.master')

@section('main_content')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<style>
  .card-premium {
    border: none;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
  }
  .form-label-premium { font-weight: 600; color: #495057; }
  .form-control-premium {
    border-radius: 8px;
    padding: .75rem 1rem;
    font-size: 1.5rem;
  }
  .form-error { color: #e74a3b; font-size: 1.575rem; margin-top: .25rem; display: none; }

  .btn-primary-premium, .btn-info-premium, .btn-danger-premium, .btn-secondary-premium {
    border-radius: 8px;
    padding: .5rem 1rem;
    font-size: 1.5rem;
  }
  .btn-primary-premium { background: #4e73df; color: #fff; border: none; }
  .btn-primary-premium:hover { background: #3b5bbf; }
  .btn-info-premium { background: #36b9cc; color: #fff; border: none; }
  .btn-info-premium:hover { background: #2c97a3; }
  .btn-danger-premium { background: #e74a3b; color: #fff; border: none; }
  .btn-danger-premium:hover { background: #c43c2f; }
  .btn-secondary-premium { background: #858796; color: #fff; border: none; }
  .btn-secondary-premium:hover { background: #6c757d; }

  table.table-premium th, table.table-premium td {
    vertical-align: middle;
  }
</style>
@endpush
<section role="main" class="content-body">
<div class="container mt-5">
    <h3 class="fw-bold text-primary mb-4"><i class="fa fa-tools me-2"></i> Maintenance Types</h3>

    {{-- Notification --}}
    <div id="notification"></div>

    {{-- Add / Edit Form --}}
    <div class="card card-premium shadow-sm mb-4">
        <div class="card-body">
            <form id="typeForm">
                @csrf
                <input type="hidden" name="type_id" id="type_id">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-premium"><i class="fa fa-tag me-1"></i> Name</label>
                        <input type="text" class="form-control form-control-premium" name="mtype_name" id="mtype_name">
                        <div class="form-error" id="error-mtype_name"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-premium"><i class="fa fa-align-left me-1"></i> Description</label>
                        <input type="text" class="form-control form-control-premium" name="description" id="description">
                        <div class="form-error" id="error-description"></div>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary-premium" id="submitBtn">
                        <i class="fa fa-save me-2"></i> Save
                    </button>
                    <button type="button" class="btn btn-secondary-premium" id="resetBtn">
                        <i class="fa fa-undo me-2"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
<br>
    {{-- Types Table --}}
    <div class="card card-premium shadow-sm">
        <div class="card-body">
            <table class="table table-hover table-premium" id="typesTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($types as $type)
                    <tr id="row-{{ $type->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $type->name }}</td>
                        <td>{{ $type->description }}</td>
                        <td>
                            <button class="btn btn-sm btn-info-premium editBtn" data-id="{{ $type->id }}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger-premium deleteBtn" data-id="{{ $type->id }}">
                                <i class="fa fa-minus"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</section>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function(){

    // ---------- AJAX Submit with Inline Validation
    $('#typeForm').on('submit', function(e){
        e.preventDefault();
        $('.form-error').hide().text(''); // reset errors
        let type_id = $('#type_id').val();
        let url = type_id ? `{{ url('maintenance-types/update') }}/${type_id}` : `{{ route('maintenance.types.store') }}`;

        $('#submitBtn').html('<i class="fa fa-spinner fa-spin me-2"></i> Saving...').prop('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: $('#typeForm').serialize(),
            success: function(res){
                Swal.fire({icon:'success', title:'Success', text: res.message || 'Saved successfully.', timer:1200, showConfirmButton:false})
                    .then(()=>location.reload());
            },
            error: function(xhr){
                if(xhr.status === 422 && xhr.responseJSON.errors){
                    // show field errors
                    Object.keys(xhr.responseJSON.errors).forEach(function(key){
                        $(`#error-${key}`).text(xhr.responseJSON.errors[key][0]).show();
                    });
                    Swal.fire({icon:'error', title:'Validation Failed', text:'Please fix the errors below.'});
                } else {
                    Swal.fire({icon:'error', title:'Error', text: xhr.responseJSON?.message || 'Something went wrong.'});
                }
            },
            complete: function(){ $('#submitBtn').html('<i class="fa fa-save me-2"></i> Save').prop('disabled', false); }
        });
    });

    // ---------- Reset Form
    $('#resetBtn').click(function(){
        $('#typeForm')[0].reset();
        $('#type_id').val('');
        $('.form-error').hide().text('');
    });

    // ---------- Edit
    $('.editBtn').click(function(){
        let id = $(this).data('id');
        $.get(`{{ url('maintenance-types/edit') }}/${id}`, function(data){
            $('#type_id').val(data.id);
            $('#name').val(data.name);
            $('#description').val(data.description);
        });
    });

    // ---------- Delete with SweetAlert2 Confirmation
    $('.deleteBtn').click(function(){
        let id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74a3b',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if(result.isConfirmed){
                $.ajax({
                    url: `{{ url('maintenance-types/delete') }}/${id}`,
                    type: 'DELETE',
                    data: {_token: '{{ csrf_token() }}'},
                    success: function(res){
                        Swal.fire({icon:'success', title:'Deleted', text: res.message || 'Type deleted.'});
                        $(`#row-${id}`).fadeOut();
                    },
                    error: function(){ Swal.fire({icon:'error', title:'Error', text:'Unable to delete.'}); }
                });
            }
        });
    });

});
</script>
@endpush

@endsection
