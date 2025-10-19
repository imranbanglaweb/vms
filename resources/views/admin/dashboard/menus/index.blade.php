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
    border-radius: 3px;
    border-color: #D4D4D4 #D4D4D4 #BCBCBC;
    padding: 6px;
    cursor: move;
    background: #f6f6f6;
    background: linear-gradient(to bottom, #ffffff 0%,#f6f6f6 47%,#ededed 100%);
}
</style>

<section role="main" class="content-body">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Menu Manage</h2>
            </div>
            <div class="pull-right">
                @can('menu-create')
                    <a class="btn btn-success" href="{{ route('menus.create') }}">
                        <i class="fa fa-plus"></i> Add Menu
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div id="successMessage">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        @if ($message = Session::get('danger'))
            <div class="alert alert-danger">
                <p>{{ $message }}</p>
            </div>
        @endif
    </div>

    <section class="panel">
        <header class="panel-heading">
            <div class="panel-actions">
                <a href="#" class="fa fa-caret-down"></a>
                <a href="#" class="fa fa-times"></a>
            </div>
        </header>
        <!-- Alert container will be inserted here -->
        <div class="panel-body">
            <table class="table table-bordered table-striped mb-none" id="myTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th width="15%">Menu Type</th>
                        <th>Menu Icon</th>
                        <th>Menu URL</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="menu_list">
                    @foreach ($menus as $key => $menu)
                        <tr id="{{ $menu->id }}">
                            <td>{{ ++$i }}</td>
                            <td>{{ $menu->menu_name }}</td>
                            <td>{{ $menu->menu_type }}</td>
                            <td><i class="{{ $menu->menu_icon }}"></i></td>
                            <td>{{ $menu->menu_url }}</td>
                            <td>
                                @can('menu-edit')
                                    <a class="btn btn-primary" href="{{ route('menus.edit',$menu->id) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endcan
                                @can('menu-delete')
                                    <button class="btn btn-danger deleteUser" data-menuid="{{$menu->id}}">
                                        <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <div id="applicantDeleteModal" class="modal modal-danger fade">
        <div class="modal-dialog" style="width:55%;">
            <div class="modal-content">
                {!! Form::open(['method' => 'DELETE','route' => ['menus.destroy', $menu->id],'style'=>'display:inline']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title text-center">Delete Menu</h4>
                </div>
                <div class="modal-body">
                    <h4 class="text-center text-danger">Are You Sure Delete Menu?</h4>
                    <input type="hidden" name="menu_id" id="menu_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger waves-effect remove-data-from-delete-form">Delete</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    {!! $menus->render() !!}
</section>

@push('styles')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
/* Add styles for the alert */
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.alert-dismissible {
    padding-right: 4rem;
}

.alert-dismissible .close {
    position: absolute;
    top: 0;
    right: 0;
    padding: 0.75rem 1.25rem;
    color: inherit;
}

.fade {
    transition: opacity 0.15s linear;
}

.fade.show {
    opacity: 1;
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
$(document).ready(function() {
    // Define showAlert function
    function showAlert(type, message) {
        var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show">' +
            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
            message +
            '</div>';
            
        // Remove any existing alerts
        $('#alert-container').remove();
        
        // Add alert container after the header
        $('.panel-heading').after('<div id="alert-container">' + alertHtml + '</div>');
        
        // Auto hide after 3 seconds
        setTimeout(function() {
            $('#alert-container').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 3000);
    }

    $("#menu_list").sortable({
        items: "tr",
        cursor: "move",
        opacity: 0.6,
        update: function(event, ui) {
            var order = $(this).sortable("serialize");
            $.ajax({
                url: '{{ route("menus.reorder") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    itemID: ui.item.attr('id'),
                    itemIndex: ui.item.index() + 1
                },
                success: function(response) {
                    if(response.success) {
                        showAlert('success', 'Menu order updated successfully');
                    } else {
                        showAlert('error', response.message || 'Error updating menu order');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    showAlert('error', 'Error updating menu order');
                }
            });
        }
    }).disableSelection();

    // Session message auto-hide
    $("#successMessage").delay(3000).slideUp(300);

    // Delete modal handler
    $(document).on('click', '.deleteUser', function() {
        var menu_id = $(this).attr('data-menuid');
        $('#menu_id').val(menu_id);
        $('#applicantDeleteModal').modal('show');
    });
});
</script>
@endpush

@endsection