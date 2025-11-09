@extends('admin.dashboard.master')

@section('main_content')
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
@endpush
<br>
<br>
<br>
<br>
<section role="main" class="content-body">
<div class="d-flex justify-content-between align-items-center mb-3">
	<h3>Drivers</h3>
	<div>
		<a  href="{{ route('drivers.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Create Driver</a>
		<button id="refresh-table" class="btn btn-secondary"><i class="fa fa-sync"></i></button>
	</div>

	
</div>

<div class="row mb-3">
	<div class="col-8">
		<input id="table-search" class="form-control" placeholder="Search drivers...">
	</div>
</div>

<table class="table table-striped" id="drivers-table">
	<thead>
		<tr>
			<th>#</th>
			<th>Name</th>
			<th>license Number</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>
</section>
@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function(){
	$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'X-Requested-With': 'XMLHttpRequest' } });
	var csrfToken = $('meta[name="csrf-token"]').attr('content');
	var table = $('#drivers-table').DataTable({
		processing:true,
		serverSide:true,
		ajax: '{!! route('drivers.data') !!}',
		columns:[
			{data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false},
			{data:'driver_name', name:'driver_name'},
			{data:'license_number', name:'license_number'},
			{data:'action', name:'action', orderable:false, searchable:false}
		],
		order:[[1,'asc']],
		responsive: true,
		lengthChange: true,
		pageLength: 10
	});

	// DataTables error handler
	$('#drivers-table').on('error.dt', function(e, settings, techNote, message){
		console.error('DataTables error:', message);
		try { if (settings && settings.jqXHR && settings.jqXHR.responseText) console.error('Server response:', settings.jqXHR.responseText); } catch(err) { console.error(err); }
	});

	$('#refresh-table').on('click', function(){ table.ajax.reload(null, false); });
	$('#table-search').on('keyup change', function(){ table.search(this.value).draw(); });

	// open create modal
	$('#openCreateModal').on('click', function(){ clearForm(); $('#driverModalTitle').text('Create Driver'); $('#driverModal').modal('show'); });

	// edit
	$(document).on('click', '.editDriver', function(){
		clearForm();
		var id = $(this).data('id');
		var name = $(this).data('name');
		var phone = $(this).data('phone');
		$('#driver_id').val(id);
		$('#name').val(name);
		$('#phone').val(phone);
		$('#driverModalTitle').text('Edit Driver');
		$('#driverModal').modal('show');
	});

	// submit
	$('#driverForm').on('submit', function(e){
		e.preventDefault();
		clearErrors();
		var data = { id: $('#driver_id').val(), name: $('#name').val(), phone: $('#phone').val() };
		$('#saveDriverBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving');
		$.ajax({ url: '{!! route('drivers.store') !!}', method: 'POST', data: data, success: function(res){
			$('#driverModal').modal('hide');
			$('#saveDriverBtn').prop('disabled', false).html('<i class="fa fa-save"></i> Save');
			table.ajax.reload(null,false);
			showToast('Saved', 'Driver saved successfully', 'success');
		}, error: function(xhr){
			$('#saveDriverBtn').prop('disabled', false).html('<i class="fa fa-save"></i> Save');
			if (xhr.status===422||xhr.status===400){
				var errors = xhr.responseJSON.errors || xhr.responseJSON;
				if (Array.isArray(errors)){ alert(errors.join('\n')); } else { /* per-field handling can be added */ }
			} else { showToast('Error', 'Server error', 'error'); }
		} });
	});

	// delete (use POST + _method and include CSRF)
	var deleteUrl = null;
	$(document).on('click', '.deleteUser', function(){ deleteUrl = '{{ url('drivers') }}' + '/' + $(this).data('did'); $('#confirmDeleteModal').modal('show'); });
	$('#confirmDelete').on('click', function(){
		if(!deleteUrl) return;
		var $btn = $(this);
		$btn.prop('disabled', true).text('Deleting...');
		$.ajax({ url: deleteUrl, method: 'POST', data: { _method: 'DELETE', _token: csrfToken }, success:function(res){
				$('#confirmDeleteModal').modal('hide');
				$btn.prop('disabled', false).text('Delete');
				showToast('Deleted', res.message || 'Driver deleted', 'danger');
				table.ajax.reload(null,false);
		}, error: function(xhr){
				$btn.prop('disabled', false).text('Delete');
				var msg = 'Delete failed';
				if (xhr && xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
				showToast('Error', msg, 'error');
		} });
	});

	function clearForm(){ $('#driverForm')[0].reset(); $('#driver_id').val(''); clearErrors(); }
	function clearErrors(){ $('.invalid-feedback').empty().hide(); $('.form-control').removeClass('is-invalid'); }
		// toast helper using SweetAlert2
		function showToast(title, message, type){
			var icon = (type === 'success') ? 'success' : (type === 'danger' ? 'error' : 'warning');
			const Toast = Swal.mixin({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 3000,
				timerProgressBar: true,
			});
			Toast.fire({ icon: icon, title: message });
		}
});
</script>
@endpush

<!-- driver modal -->
<div id="driverModal" class="modal fade" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="driverModalTitle">Create Driver</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><form id="driverForm"><div class="modal-body"><input type="hidden" id="driver_id" name="id"><div class="form-group"><label for="name">Name</label><input type="text" id="name" name="name" class="form-control"><div class="invalid-feedback" id="err_name"></div></div><div class="form-group"><label for="phone">Phone</label><input type="text" id="phone" name="phone" class="form-control"><div class="invalid-feedback" id="err_phone"></div></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button><button type="submit" id="saveDriverBtn" class="btn btn-primary"><i class="fa fa-save"></i> Save</button></div></form></div></div></div>

<!-- confirm modal -->
<div id="confirmDeleteModal" class="modal fade" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Confirm</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body">Are you sure?</div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="button" id="confirmDelete" class="btn btn-danger">Delete</button></div></div></div></div>

@endsection
