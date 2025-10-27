@extends('admin.dashboard.master')


@section('main_content')
<style>
  /* small modern tweaks for the modal and validation */
  .modal-modern .modal-content { border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,0.12); }
  .invalid-feedback i { margin-right:6px; color: #c0392b; }
  .ajax-success { color: #27ae60; }
  .ajax-error { color: #c0392b; }
  .action-btns .btn { margin-right:4px; }
</style>
<section role="main" class="content-body">
<div class="row">
    <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center">
        <h2>Unit Manage</h2>
        <div>
          @can('unit-create')
            <button id="openCreateModal" class="btn btn-success"><i class="fa fa-plus"></i> Create Unit</button>
          @endcan
          <button id="refresh-table" class="btn btn-secondary"><i class="fa fa-sync"></i></button>
        </div>
    </div>
    <div class="col-12 mt-2">
      <input id="table-search" class="form-control" placeholder="Search units..." style="max-width:480px;">
    </div>
</div>

<div class="row mt-3">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <table class="table table-hover table-striped" id="units-table" style="width:100%">
          <thead>
            <tr>
              <th>#</th>
              <th>Unit Name</th>
              <th>Unit Code</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @if(!empty($units) && $units->count())
              @foreach($units as $unitItem)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $unitItem->unit_name }}</td>
                  <td>{{ $unitItem->unit_code }}</td>
                  <td class="action-btns">
                    <button class="btn btn-sm btn-primary editUnit" data-id="{{ $unitItem->id }}" data-name="{{ $unitItem->unit_name }}" data-code="{{ $unitItem->unit_code }}"><i class="fa fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger deleteUser" data-uid="{{ $unitItem->id }}"><i class="fa fa-trash"></i></button>
                  </td>
                </tr>
              @endforeach
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Create/Edit Modal --}}
<div id="unitModal" class="modal fade modal-modern" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="unitModalTitle">Create Unit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form id="unitForm">
        <div class="modal-body">
          <div id="formAlert" role="alert" style="display:none"></div>
          <input type="hidden" name="id" id="unit_id">
          <div class="form-group">
            <label for="unit_name">Unit Name</label>
            <input type="text" class="form-control" id="unit_name" name="unit_name">
            <div class="invalid-feedback" id="err_unit_name"></div>
          </div>
          <div class="form-group">
            <label for="unit_code">Unit Code</label>
            <input type="text" class="form-control" id="unit_code" name="unit_code">
            <div class="invalid-feedback" id="err_unit_code"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="saveUnitBtn"><i class="fa fa-save"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Delete modal (keeps original markup but we'll submit via AJAX) --}}
<div id="applicantDeleteModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-danger">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Delete Unit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div class="modal-body text-center">
        <p class="text-danger"><i class="fa fa-exclamation-triangle fa-2x"></i></p>
        <p>Are you sure you want to delete this unit?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDelete" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<style> #units-table thead th { font-weight:600; } </style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function(){
  // CSRF + X-Requested-With for AJAX (helps server detect AJAX requests and avoid HTML redirects)
  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'X-Requested-With': 'XMLHttpRequest' } });

    var table = $('#units-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('units.data') !!}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'unit_name', name: 'unit_name' },
            { data: 'unit_code', name: 'unit_code' },
            { data: 'action', name: 'action', orderable:false, searchable:false }
        ],
        order: [[1, 'asc']],
        responsive: true,
        lengthChange: true,
        pageLength: 10
    });

  // When DataTables fails to parse the JSON response, log response body for debugging
  $('#units-table').on('error.dt', function(e, settings, techNote, message){
    console.error('DataTables error:', message);
    try {
      // settings.jqXHR may be available depending on how DataTables called AJAX
      if (settings && settings.jqXHR && settings.jqXHR.responseText) {
        console.error('Server response body:', settings.jqXHR.responseText);
      }
    } catch (err) {
      console.error('Failed to read responseText for debugging', err);
    }
  });

    // search input
    $('#table-search').on('keyup change', function(){ table.search(this.value).draw(); });
    $('#refresh-table').on('click', function(){ table.ajax.reload(null, false); });

    // open create modal
    $('#openCreateModal').on('click', function(){
        clearForm();
        $('#unitModalTitle').text('Create Unit');
        $('#unitModal').modal('show');
    });

    // open edit modal (buttons from server-side contain editUnit class)
    $(document).on('click', '.editUnit', function(){
        clearForm();
        var id = $(this).data('id');
        var name = $(this).data('name');
        var code = $(this).data('code');
        $('#unit_id').val(id);
        $('#unit_name').val(name);
        $('#unit_code').val(code);
        $('#unitModalTitle').text('Edit Unit');
        $('#unitModal').modal('show');
    });

    // submit create/edit via AJAX (store uses updateOrCreate)
    $('#unitForm').on('submit', function(e){
        e.preventDefault();
        clearErrors();
        var data = { id: $('#unit_id').val(), unit_name: $('#unit_name').val(), unit_code: $('#unit_code').val() };
        $('#saveUnitBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving');

        $.ajax({
            url: '{!! route('units.store') !!}',
            method: 'POST',
            data: data,
            success: function(res){
                $('#unitModal').modal('hide');
                $('#saveUnitBtn').prop('disabled', false).html('<i class="fa fa-save"></i> Save');
                showToast('Saved', 'Unit saved successfully', 'success');
                table.ajax.reload(null, false);
            },
            error: function(xhr){
                $('#saveUnitBtn').prop('disabled', false).html('<i class="fa fa-save"></i> Save');
                if (xhr.status === 422 || xhr.status === 400) {
                    var json = xhr.responseJSON;
                    var errors = json.errors || json;
                    // errors may be array or object
                    if (Array.isArray(errors)) {
                        // generic array of messages
                        $('#formAlert').html('<div class="alert alert-danger"><i class="fa fa-times-circle"></i> '+errors.join('<br>')+'</div>').show();
                    } else {
                        // object keyed by field
                        $.each(errors, function(key, val){
                            var msg = Array.isArray(val)? val.join('<br>') : val;
                            $('#err_'+key).html('<i class="fa fa-times-circle"></i> '+msg).show();
                            $('#'+key).addClass('is-invalid');
                        });
                    }
                } else {
                    $('#formAlert').html('<div class="alert alert-danger"><i class="fa fa-times-circle"></i> Unexpected server error</div>').show();
                }
            }
        });
    });

    // clear form / errors
    function clearForm(){
        $('#unitForm')[0].reset();
        $('#unit_id').val('');
        clearErrors();
        $('#formAlert').hide().empty();
    }
    function clearErrors(){
        $('.invalid-feedback').empty().hide();
        $('.form-control').removeClass('is-invalid');
    }

    // simple toast helper -> inject near top of page
    function showToast(title, message, type){
        // small inline notification (you can replace with SweetAlert2 or toastr)
        var cls = (type==='success')? 'alert-success' : 'alert-danger';
        var $el = $('<div class="alert '+cls+'" style="position:fixed; right:20px; top:20px; z-index:1060; min-width:220px;"><strong>'+title+'</strong><div>'+message+'</div></div>');
        $('body').append($el);
        setTimeout(function(){ $el.fadeOut(400, function(){ $el.remove(); }); }, 3000);
    }

    // delete flow: open confirm then AJAX delete
    var deleteUrl = null;
    $(document).on('click', '.deleteUser', function(){
        var uid = $(this).data('uid');
        deleteUrl = '{{ url('units') }}' + '/' + uid;
        $('#applicantDeleteModal').modal('show');
    });

    $('#confirmDelete').on('click', function(){
        if (!deleteUrl) return;
        $(this).prop('disabled', true).text('Deleting...');
        $.ajax({ url: deleteUrl, method: 'DELETE', success: function(res){
            $('#applicantDeleteModal').modal('hide');
            $('#confirmDelete').prop('disabled', false).text('Delete');
            showToast('Deleted', res.message || 'Unit deleted', 'success');
            table.ajax.reload(null, false);
        }, error: function(){
            $('#confirmDelete').prop('disabled', false).text('Delete');
            showToast('Error', 'Delete failed', 'error');
        }});
    });

});
</script>
@endpush
@endsection
