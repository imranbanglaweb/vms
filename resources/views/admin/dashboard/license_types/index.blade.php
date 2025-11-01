@extends('admin.dashboard.master')

@section('main_content')
<section class="content-body">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>License Types</h3>
            <a href="{{ route('license-types.create') }}" class="btn btn-primary">Create</a>
        </div>

        <div class="card">
            <div class="card-body">
                <table id="licenseTypesTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    $(function(){
        var table = $('#licenseTypesTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route("license-types.index") }}',
                type: 'GET'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'type_name', name: 'type_name' },
                { data: 'description', name: 'description', render: function(data){ return data ? data.substring(0, 80) : ''; } },
                { data: 'status', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>t<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                { extend: 'copy', className: 'btn btn-sm btn-outline-secondary' },
                { extend: 'csv', className: 'btn btn-sm btn-outline-secondary' },
                { extend: 'excel', className: 'btn btn-sm btn-outline-secondary' },
                { extend: 'pdf', className: 'btn btn-sm btn-outline-secondary' },
                { extend: 'print', className: 'btn btn-sm btn-outline-secondary' }
            ],
            order: [[0, 'desc']],
            language: { processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>' }
        });

        // Resize columns when modal/visibility changes
        $(document).on('shown.bs.tab shown.bs.modal', function(){ table.columns.adjust(); });
    });
</script>
@endpush

@endsection
