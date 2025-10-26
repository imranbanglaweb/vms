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
            <h2>Unit Manage</h2>
        </div>
        <div class="pull-right">
        @can('unit-create')
            <a class="btn btn-success" href="{{ route('units.create') }}"> <i class="fa fa-plus"></i>    Add Unit</a>
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
      <input id="table-search" class="form-control" placeholder="Search units..." style="min-width:260px;">
    </div>
    <div>
      @can('unit-create')
      <a class="btn btn-success" href="{{ route('units.create') }}"> <i class="fa fa-plus"></i> Add Unit</a>
      @endcan
    </div>
  </div>

  <table class="table table-hover table-striped" id="units-table" style="width:100%">
    <thead>
      <tr>
        <th>#</th>
        <th>Unit Name</th>
        <th>Unit Code</th>
        <th>Description</th>
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
<div id="applicantDeleteModal" class="modal modal-danger fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
            <div class="modal-content">

                    <form id="deleteUnitForm" method="POST" action="" style="display:inline">
                        @csrf
                        @method('DELETE')
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title text-center" id="custom-width-modalLabel">
                        Delete Unit</h4>
                    </div>
                    <div class="modal-body">
                        <h4 class="text-center text-danger">Are You Sure Delete Unit</h4>
                        <input type="hidden" name="u_id" id="u_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger waves-effect remove-data-from-delete-form">Delete</button>
                    </div>

                    </form>
            </div>
        </div>
</div>
</section>
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<style>
    #units-table thead th { font-weight:600; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function(){
    var table = $('#units-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('units.data') !!}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'unit_name', name: 'unit_name' },
            { data: 'unit_code', name: 'unit_code' },
            { data: 'remarks', name: 'remarks' },
            { data: 'action', name: 'action', orderable:false, searchable:false }
        ],
        order: [[1, 'asc']],
        responsive: true,
        lengthChange: true,
        pageLength: 10
    });

    $('#table-search').on('keyup change', function(){
        table.search(this.value).draw();
    });

    // delete handler: open generic modal, set id and form action
    $(document).on('click', '.deleteUser', function(){
        var uid = $(this).data('uid') || $(this).attr('data-uid');
        if(!uid) uid = $(this).data('uid');
        $('#u_id').val(uid);
        // set the delete form action to /units/{id}
        var base = "{{ url('units') }}";
        $('#deleteUnitForm').attr('action', base + '/' + uid);
        $('#applicantDeleteModal').modal('show');
    });
});
</script>
@endpush
@endsection