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
        <h2>Location Manage</h2>
        <div>
          @can('unit-create')
            <button id="openCreateModal" class="btn btn-success"><i class="fa fa-plus"></i> Create Location</button>
          @endcan
          <button id="refresh-table" class="btn btn-secondary"><i class="fa fa-sync"></i></button>
        </div>
    </div>
    <div class="col-12 mt-2">
      <input id="table-search" class="form-control" placeholder="Search locations..." style="max-width:480px;">
    </div>
</div>

<div class="row mt-3">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <table class="table table-hover table-striped" id="locations-table" style="width:100%">
          <thead>
            <tr>
              <th>#</th>
              <th>Location Name</th>
              <th>Unit</th>
              <th>Address</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @if(!empty($location_lists) && $location_lists->count())
              @foreach($location_lists as $loc)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $loc->location_name }}</td>
                  <td>{{ $loc->unit_name }}</td>
                  <td>{{ $loc->address }}</td>
                  <td class="action-btns">
                    <button class="btn btn-sm btn-primary editLocation" data-id="{{ $loc->l_id }}" data-name="{{ $loc->location_name }}" data-address="{{ $loc->address }}" data-unit="{{ $loc->unit_name }}"><i class="fa fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger deleteUser" data-lid="{{ $loc->l_id }}"><i class="fa fa-trash"></i></button>
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
<div id="locationModal" class="modal fade modal-modern" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="locationModalTitle">Create Location</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form id="locationForm">
        <div class="modal-body">
          <div id="formAlert" role="alert" style="display:none"></div>
          <input type="hidden" name="id" id="location_id">
          <div class="form-group">
            <label for="location_name">Location Name</label>
            <input type="text" class="form-control" id="location_name" name="location_name">
            <div class="invalid-feedback" id="err_location_name"></div>
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address" name="address">
            <div class="invalid-feedback" id="err_address"></div>
          </div>
          <div class="form-group">
            <label for="unit_id">Unit</label>
            <select id="unit_id" name="unit_id" class="form-control">
              <option value="">-- Select Unit --</option>
              @if(!empty($units))
                @foreach($units as $u)
                  <option value="{{ $u->id }}">{{ $u->unit_name }}</option>
                @endforeach
              @endif
            </select>
            <div class="invalid-feedback" id="err_unit_id"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="saveLocationBtn"><i class="fa fa-save"></i> Save</button>
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
        <h5 class="modal-title">Delete Location</h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div class="modal-body text-center">
        <p class="text-danger"><i class="fa fa-exclamation-triangle fa-2x"></i></p>
        <p>Are you sure you want to delete this location?</p>
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
<style> #locations-table thead th { font-weight:600; } </style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function(){
    // CSRF + X-Requested-With for AJAX
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'X-Requested-With': 'XMLHttpRequest' } });

    var table = $('#locations-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('locations.data') !!}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'location_name', name: 'location_name' },
            { data: 'unit_name', name: 'unit_name' },
            { data: 'address', name: 'address' },
            { data: 'action', name: 'action', orderable:false, searchable:false }
        ],
        order: [[1, 'asc']],
        responsive: true,
        lengthChange: true,
        pageLength: 10
    });

    // DataTables error handler
    $('#locations-table').on('error.dt', function(e, settings, techNote, message){
        console.error('DataTables error:', message);
        try {
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
        $('#locationModalTitle').text('Create Location');
        $('#locationModal').modal('show');
    });

    // open edit modal
    $(document).on('click', '.editLocation', function(){
        clearForm();
        var id = $(this).data('id');
        var name = $(this).data('name');
        var address = $(this).data('address');
        var unit = $(this).data('unit');
        $('#location_id').val(id);
        $('#location_name').val(name);
        $('#address').val(address);
        if (unit) { $('#unit_id option').filter(function(){ return $(this).text()===unit; }).prop('selected', true); }
        $('#locationModalTitle').text('Edit Location');
        $('#locationModal').modal('show');
    });

    // submit create/edit via AJAX
    $('#locationForm').on('submit', function(e){
        e.preventDefault();
        clearErrors();
        var data = { id: $('#location_id').val(), unit_id: $('#unit_id').val(), location_name: $('#location_name').val(), address: $('#address').val() };
        $('#saveLocationBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving');

        $.ajax({
            url: '{!! route('locations.store') !!}',
            method: 'POST',
            data: data,
            success: function(res){
                $('#locationModal').modal('hide');
                $('#saveLocationBtn').prop('disabled', false).html('<i class="fa fa-save"></i> Save');
                showToast('Saved', 'Location saved successfully', 'success');
                table.ajax.reload(null, false);
            },
            error: function(xhr){
                $('#saveLocationBtn').prop('disabled', false).html('<i class="fa fa-save"></i> Save');
                if (xhr.status === 422 || xhr.status === 400) {
                    var json = xhr.responseJSON;
                    var errors = json.errors || json;
                    if (Array.isArray(errors)) {
                        $('#formAlert').html('<div class="alert alert-danger"><i class="fa fa-times-circle"></i> '+errors.join('<br>')+'</div>').show();
                    } else {
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

    function clearForm(){ $('#locationForm')[0].reset(); $('#location_id').val(''); clearErrors(); $('#formAlert').hide().empty(); }
    function clearErrors(){ $('.invalid-feedback').empty().hide(); $('.form-control').removeClass('is-invalid'); }

    // toast helper
    function showToast(title, message, type){
        var cls = (type==='success')? 'alert-success' : 'alert-danger';
        var $el = $('<div class="alert '+cls+'" style="position:fixed; right:20px; top:20px; z-index:1060; min-width:220px;"><strong>'+title+'</strong><div>'+message+'</div></div>');
        $('body').append($el);
        setTimeout(function(){ $el.fadeOut(400, function(){ $el.remove(); }); }, 3000);
    }

  // delete flow
  var deleteUrl = null;
  var csrfToken = $('meta[name="csrf-token"]').attr('content');
  $(document).on('click', '.deleteUser', function(){
    var lid = $(this).data('lid');
    deleteUrl = '{{ url('locations') }}' + '/' + lid;
    $('#applicantDeleteModal').modal('show');
  });

  $('#confirmDelete').on('click', function(){
    if (!deleteUrl) return;
    $(this).prop('disabled', true).text('Deleting...');
    $.ajax({ url: deleteUrl, method: 'POST', data: { _method: 'DELETE', _token: csrfToken }, success: function(res){
      $('#applicantDeleteModal').modal('hide');
      $('#confirmDelete').prop('disabled', false).text('Delete');
      // show danger-styled toast for deletions per UX request
      showToast('Deleted', res.message || 'Location deleted', 'danger');
      table.ajax.reload(null, false);
    }, error: function(xhr){
      $('#confirmDelete').prop('disabled', false).text('Delete');
      var msg = 'Delete failed';
      if (xhr && xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
      showToast('Error', msg, 'error');
    }});
  });

});
</script>
@endpush
</section>
@endsection