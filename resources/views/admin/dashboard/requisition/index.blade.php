@extends('admin.dashboard.master')

@section('main_content')
<br>
<br>
<br>
<section role="main" class="content-body" style="body-background: #f2f4f8;">

<div class="container-fluid p-4">

    <div class="card shadow-sm border-0 bg-white">
        <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
            <h3 class="m-0"><i class="fa fa-list me-2 text-primary"></i> Requisition List</h3>

            <a href="{{ route('requisitions.create') }}" class="btn btn-success btn-sm">
                <i class="fa fa-plus"></i> Create New
            </a>
        </div>

        <div class="card-body">

            <!-- ========================= -->
            <!-- Search Filters -->
            <!-- ========================= -->
            <form id="searchForm" class="row g-3 mb-4">

                <div class="col-md-3">
                    <label class="form-label fw-bold">Employee</label>
                    <input type="text" name="employee" class="form-control form-control-lg" placeholder="Employee name">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Department</label>
                    <input type="text" name="department" class="form-control form-control-lg" placeholder="Department">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Vehicle</label>
                    <input type="text" name="vehicle" class="form-control form-control-lg" placeholder="Vehicle">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Date From</label>
                    <input type="date" name="date_from" class="form-control form-control-lg">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Date To</label>
                    <input type="date" name="date_to" class="form-control form-control-lg">
                </div>

                <div class="col-md-12 d-flex justify-content-end mt-2">
                    <button type="button" id="searchBtn" class="btn btn-primary btn-lg me-2">
                        <i class="fa fa-search"></i> Filter
                    </button>

                    <a href="{{ route('requisitions.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fa fa-refresh"></i> Reset
                    </a>
                </div>

            </form>

            <hr>

            <!-- ========================= -->
            <!-- Export Buttons -->
            <!-- ========================= -->
            <div class="mb-3 text-end">
                <a href="{{ route('requisitions.export.excel') }}" class="btn btn-success btn-sm me-2">
                    <i class="fa fa-file-excel"></i> Export Excel
                </a>

                <a href="{{ route('requisitions.export.pdf') }}" class="btn btn-danger btn-sm">
                    <i class="fa fa-file-pdf"></i> Export PDF
                </a>
            </div>

            <!-- ========================= -->
            <!-- Table -->
            <!-- ========================= -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Requested By</th>
                            <th>Department</th>
                            <th>Vehicle</th>
                            <th>Driver</th>
                            <th>Travel Date</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th width="12%">Action</th>
                        </tr>
                    </thead>

                    <tbody id="requisitionTableBody">
                        @include('admin.dashboard.requisition.table')
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="paginationLinks">
                {{ $requisitions->links() }}
            </div>

        </div>
    </div>

</div>

</section>
@endsection

@push('scripts')
<script>
$(function(){

    // ============================
    // AJAX FILTER SEARCH
    // ============================
    $('#searchBtn').click(function(){

        let data = $('#searchForm').serialize();

        $.ajax({
            url: "{{ route('requisitions.index') }}",   // FIXED
            method: "GET",
            data: data,
            success: function(res){
                $('#requisitionTableBody').html(res.table);
                $('#paginationLinks').html(res.pagination);
            }
        });

    });

    // ============================
    // AJAX PAGINATION
    // ============================
    $(document).on('click', '.pagination a', function(e){
        e.preventDefault();

        let url = $(this).attr('href');

        $.ajax({
            url: url,
            success: function(res){
                $('#requisitionTableBody').html(res.table);
                $('#paginationLinks').html(res.pagination);
            }
        });

    });

});
</script>

@endpush
