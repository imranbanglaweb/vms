@extends('admin.dashboard.master')

@section('main_content')
<section class="content-body py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-primary"><i class="fa fa-tools me-2"></i> Maintenance Requisitions</h3>
            <a href="{{ route('maintenance.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus me-1"></i> Create New</a>
        </div>

        {{-- Filters --}}
        <div class="row mb-3 g-2">
            <div class="col-md-3">
                <input type="text" id="searchVehicle" class="form-control" placeholder="Search Vehicle">
            </div>
            <div class="col-md-3">
                <input type="text" id="searchEmployee" class="form-control" placeholder="Search Employee">
            </div>
            <div class="col-md-3">
                <select id="searchType" class="form-select">
                    <option value="">All Types</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Breakdown">Breakdown</option>
                    <option value="Inspection">Inspection</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="searchPriority" class="form-select">
                    <option value="">All Priorities</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    <option value="Urgent">Urgent</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle" id="requisitionsTable">
                <thead class="table-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Requisition No</th>
                        <th>Type</th>
                        <th>Priority</th>
                        <th>Vehicle</th>
                        <th>Employee</th>
                        <th>Maintenance Date</th>
                        <th>Total Cost</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let table = $('#requisitionsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '{{ route("requisitions.index") }}',
            data: function(d) {
                d.vehicle = $('#searchVehicle').val();
                d.employee = $('#searchEmployee').val();
                d.type = $('#searchType').val();
                d.priority = $('#searchPriority').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'id', name: 'id' },
            { data: 'requisition_type', name: 'requisition_type' },
            { data: 'priority', name: 'priority' },
            { data: 'vehicle', name: 'vehicle.vehicle_no' },
            { data: 'employee', name: 'employee.name' },
            { data: 'maintenance_date', name: 'maintenance_date' },
            { data: 'grand_total', name: 'grand_total' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false },
        ]
    });

    // Filters
    $('#searchVehicle, #searchEmployee, #searchType, #searchPriority').on('change keyup', function() {
        table.draw();
    });
});
</script>
@endsection
