@extends('admin.dashboard.master')


@section('main_content')
<style>
  #menu_list {
  padding: 0px;
}
#menu_list td {
  list-style: none;
  margin-bottom: 10px;
  border: 1px solid #d4d4d4;
      -webkit-border-radius: 3px;
      -moz-border-radius: 3px;
      border-radius: 3px;
      border-color: #D4D4D4 #D4D4D4 #BCBCBC;
      padding: 6px;
      cursor: move;
      background: #f6f6f6;
      background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #ededed 100%);
      background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(47%,#f6f6f6), color-stop(100%,#ededed));
      background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
      background: -o-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
      background: -ms-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
      background: linear-gradient(to bottom,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ededed',GradientType=0 );
}
</style>
<section role="main" class="content-body">
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Department Manage</h2>
        </div>
        <div class="pull-right">
        @can('unit-create')
            <a class="btn btn-success" href="{{ route('departments.create') }}"> <i class="fa fa-plus"></i>    Add Department</a>
            @endcan
        </div>
    </div>
</div>


@if ($message = Session::get('success'))
    <div class="alert alert-success myElem">
        <p>{{ $message }}</p>
    </div>
@endif
@if ($message = Session::get('danger'))
    <div class="alert alert-danger myElem">
        <p>{{ $message }}</p>
    </div>
@endif


<section class="panel">
<header class="panel-heading">
    <div class="panel-actions">
        <a href="#" class="fa fa-caret-down"></a>
        <a href="#" class="fa fa-times"></a>
    </div>

   
</header>
                            <div class="panel-body">
  <div class="d-flex justify-content-between mb-2">
    <div>
      <input id="table-search" class="form-control" placeholder="Search departments..." style="min-width:260px;">
    </div>
    <div>
            @can('department-create')
            <a class="btn btn-success" href="javascript:void(0)" id="openCreateModal"> <i class="fa fa-plus"></i> Add Department</a>
            @endcan
    </div>
  </div>

  <table class="table table-hover table-striped" id="departments-table" style="width:100%">
    <thead>
      <tr>
        <th>#</th>
        <th>Department</th>
        <th>Code</th>
        <th>Unit</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</section>
</div>
</div>
</div>
</section>
<div id="applicantDeleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title text-center" id="custom-width-modalLabel">Delete Department</h4>
            </div>
            <div class="modal-body">
                <h4 class="text-center text-danger">Are You Sure Delete Department</h4>
                <input type="hidden" name="d_id" id="d_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="confirmDeleteDept" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>
</section>
<!-- Create / Edit modal for departments -->
<div id="departmentModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="departmentModalTitle">Create Department</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="departmentForm">
                <div class="modal-body">
                    <input type="hidden" id="department_id" name="id">
                    <div class="form-group">
                        <label for="department_name">Department Name</label>
                        <input type="text" id="department_name" name="department_name" class="form-control">
                        <div class="invalid-feedback" id="err_department_name"></div>
                    </div>
                    <div class="form-group">
                        <label for="department_code">Code</label>
                        <input type="text" id="department_code" name="department_code" class="form-control">
                        <div class="invalid-feedback" id="err_department_code"></div>
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
                    <button type="submit" id="saveDepartmentBtn" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- <script  src="{{ asset('js/')}}/function.js"></script> --}}
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function(){
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'X-Requested-With': 'XMLHttpRequest' } });
    var table = $('#departments-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('departments.data') !!}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'department_name', name: 'department_name' },
            { data: 'department_code', name: 'department_code' },
            { data: 'unit_name', name: 'unit_name' },
            { data: 'action', name: 'action', orderable:false, searchable:false }
        ],
        order: [[1,'asc']]
    });

    $('#table-search').on('keyup change', function(){ table.search(this.value).draw(); });

    // delete via AJAX when confirm in modal
    var deptDeleteUrl = null;
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $(document).on('click','.deleteUser', function(){
        var did = $(this).data('did') || $(this).attr('data-did');
        $('#d_id').val(did);
        deptDeleteUrl = '{{ url('departments') }}' + '/' + did;
        $('#applicantDeleteModal').modal('show');
    });

    $('#confirmDeleteDept').on('click', function(e){
        e.preventDefault();
        var did = $('#d_id').val();
        if (!did || !deptDeleteUrl) return;
        $(this).prop('disabled', true).text('Deleting...');
        $.ajax({ url: deptDeleteUrl, method: 'POST', data: { _method: 'DELETE', _token: csrfToken }, success: function(res){
            $('#applicantDeleteModal').modal('hide');
            $('#confirmDeleteDept').prop('disabled', false).text('Delete');
            // show danger-styled toast per UX
            showToast('Deleted', res.message || 'Department deleted', 'danger');
            table.ajax.reload(null,false);
        }, error: function(xhr){
            $('#confirmDeleteDept').prop('disabled', false).text('Delete');
            var msg = 'Delete failed';
            if (xhr && xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
            showToast('Error', msg, 'error');
        } });
    });

    // open create modal
    $('#openCreateModal').on('click', function(){ clearDeptForm(); $('#departmentModalTitle').text('Create Department'); $('#departmentModal').modal('show'); });

    // open edit modal (from editDepartment button generated by server)
    $(document).on('click', '.editDepartment', function(){ clearDeptForm(); var id = $(this).data('id'); var name = $(this).data('name'); var code = $(this).data('code'); var unit = $(this).data('unit'); $('#department_id').val(id); $('#department_name').val(name); $('#department_code').val(code); $('#unit_id').val(''); if(unit){ $('#unit_id option').filter(function(){ return $(this).text()===unit; }).prop('selected', true); } $('#departmentModalTitle').text('Edit Department'); $('#departmentModal').modal('show'); });

    // submit create/edit via AJAX
    $('#departmentForm').on('submit', function(e){ e.preventDefault(); clearDeptErrors(); $('#saveDepartmentBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>'); var data = { id: $('#department_id').val(), department_name: $('#department_name').val(), department_code: $('#department_code').val(), unit_id: $('#unit_id').val() }; $.ajax({ url: '{!! route('departments.store') !!}', method: 'POST', data: data, success: function(res){ $('#departmentModal').modal('hide'); $('#saveDepartmentBtn').prop('disabled', false).html('<i class="fa fa-save"></i> Save'); table.ajax.reload(null,false); }, error: function(xhr){ $('#saveDepartmentBtn').prop('disabled', false).html('<i class="fa fa-save"></i> Save'); if(xhr.status===422||xhr.status===400){ var errors = xhr.responseJSON.errors || xhr.responseJSON; if(Array.isArray(errors)){ alert(errors.join('\n')); } else { $.each(errors, function(i,v){ $('#err_'+i).html(v).show(); $('#'+i).addClass('is-invalid'); }); } } else { alert('Save failed'); } } }); });

    function clearDeptForm(){ $('#departmentForm')[0].reset(); $('#department_id').val(''); clearDeptErrors(); }
    function clearDeptErrors(){ $('.invalid-feedback').empty().hide(); $('.form-control').removeClass('is-invalid'); }
});
</script>
@endpush
@endsection