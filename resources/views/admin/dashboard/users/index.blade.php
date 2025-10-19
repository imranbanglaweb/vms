@extends('admin.dashboard.master')

@section('main_content')
<section role="main" class="content-body">
<div class="row">
    <div class="col-md-6">
        <h2>User Upload</h2>
        <div class="form-group">
            <select class="form-control select_employee_file">
                <option>Select User Export/Import</option>
                <option value="Import">Import</option>
                <option value="Export">Export</option>
            </select>
        </div>
        <div id="showImport" class="myDiv" style="display:none;">
            <form action="{{ route('employee.importuser')}}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                <div class="form-group">
                    <input type="file" name="file" class="form-control-file" required>
                </div>
                <button class="btn btn-info"><i class="fa fa-download" aria-hidden="true"></i> Import</button>
            </form>
        </div>
        <div id="showExport" class="myDiv" style="display:none;">
            <form method="POST" action="{{ route('employee.export')}}" enctype="multipart/form-data">
                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                <button class="btn btn-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export</button>
            </form>
        </div>
    </div>
    <div class="col-md-6 text-right">
        <h2>Users Management</h2>
        <div class="btn-group mb-3">
            <a class="btn btn-success" href="{{ route('users.create') }}"> <i class="fa fa-plus"></i> Add New User</a>
        </div>
        <div class="form-group">
            <input type="text" id="search" class="form-control" placeholder="Search Users">
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<div id="userTable">
    @include('admin.dashboard.users.partials.user_table', ['users' => $users])
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $(".myElem").show().delay(5000).fadeOut();
        $("#menu_list").sortable({
            stop: function() {
                $.map($(this).find('tr'), function(el) {
                    var itemID = el.id;
                    var itemIndex = $(el).index();
                    $.ajax({
                        url: '{{URL::to("order-menu")}}',
                        type: 'GET',
                        dataType: 'json',
                        data: { itemID: itemID, itemIndex: itemIndex },
                    });
                });
            }
        });

        $(document).on('click', '.deleteUser', function() {
            var q_id = $(this).attr('data-qid');
            var action = '{{ route('users.destroy', ':id') }}';
            action = action.replace(':id', q_id);
            $('#deleteForm').attr('action', action);
            $('#deleteModal').modal('show');
        });

        $('.select_employee_file').on('change', function() {
            var demovalue = $(this).val();
            $(".myDiv").hide();
            $("#show" + demovalue).show();
        });

        $('#search').on('keyup', function() {
            var query = $(this).val();
            $.ajax({
                url: "{{ route('users.search') }}",
                type: "GET",
                data: { 'search': query },
                success: function(data) {
                    $('#userTable').html(data);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + ' - ' + error);
                }
            });
        });

        // Initialize DataTable
        $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('users.getData') }}",
                type: 'GET',
                dataSrc: function(json) {
                    if (json.data) {
                        return json.data;
                    } else {
                        console.error('Invalid JSON response:', json);
                        return [];
                    }
                },
                error: function(xhr, error, thrown) {
                    if (error === 'parsererror') {
                        console.error('DataTable AJAX Error: Invalid JSON response');
                    } else {
                        console.error('DataTable AJAX Error: ' + error);
                    }
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'user_name', name: 'user_name' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'user_image', name: 'user_image', orderable: false, searchable: false, render: function(data, type, full, meta) {
                    return '<img src="{{ asset('storage/images') }}/' + data + '" height="50" onerror="this.onerror=null;this.src=\'{{ asset('images/default.png') }}\';"/>';
                }},
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });

        function confirmDelete(userId) {
            if (confirm('Are You Sure You Want to Delete this user?')) {
                deleteUser(userId);
            }
        }

    });
</script>
@endsection