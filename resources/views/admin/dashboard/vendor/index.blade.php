@extends('admin.dashboard.master')

@section('main_content')
<br>
<br>
<br>
<br>
<br>
<section role="main" class="content-body">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-truck"></i> Vendor Management</h4>

            <div class="btn-group">
                <a href="{{ route('vendors.create') }}" class="btn btn-lightbtn btn-success  btn-sm">
                    <i class="bi bi-plus-circle"></i> Add Vendor
                </a>
                <button id="refreshTable" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-repeat"></i> Refresh
                </button>
                <button id="exportExcel" class="btn btn-success btn-sm">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </button>
                <button id="exportPDF" class="btn btn-danger btn-sm">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </button>
                <br>
            </div>
        </div>
        <br>

        <div class="card-body position-relative">

            <!-- 🔄 Loading Overlay -->
            <div id="loadingOverlay" 
                 class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-white" 
                 style="z-index:10; display:none;">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <!-- 📋 Vendor DataTable -->
            <div class="table-responsive">
                <table id="vendorsTable" class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Vendor Name</th>
                            <!-- <th>Vendor Type</th> -->
                            <th>Contact Person</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
$(document).ready(function () {
    $('#loadingOverlay').show();

    let table = $('#vendorsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('vendors.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            // { data: 'vendor_type', name: 'vendor_type' },
            { data: 'contact_person', name: 'contact_person' },
            { data: 'contact_number', name: 'contact_number' },
            { data: 'email', name: 'email' },
            { 
                data: 'status', 
                name: 'status',
                render: function (data) {
                    return data === 'Active'
                        ? `<span class="badge bg-success"><i class="bi bi-check-circle"></i> ${data}</span>`
                        : `<span class="badge bg-secondary"><i class="bi bi-x-circle"></i> ${data}</span>`;
                }
            },
            {
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <a href="/vendors/${row.id}/edit" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteVendor(${row.id})">
                            <i class="bi bi-trash"></i>
                        </button>`;
                }
            }
        ],
        drawCallback: function () {
            $('#loadingOverlay').hide();
        }
    });

    // 🔁 Refresh Table
    $('#refreshTable').on('click', function() {
        $('#loadingOverlay').show();
        table.ajax.reload(() => $('#loadingOverlay').hide(), false);
    });

    // 📤 Export buttons (placeholder)
    $('#exportExcel').on('click', function() {
        alert('Excel Export feature coming soon!');
    });

    $('#exportPDF').on('click', function() {
        alert('PDF Export feature coming soon!');
    });
});

function deleteVendor(id) {
    if (!confirm('Are you sure you want to delete this vendor?')) return;
    $.ajax({
        url: `/vendors/${id}`,
        type: 'DELETE',
        data: { _token: '{{ csrf_token() }}' },
        success: function () {
            $('#vendorsTable').DataTable().ajax.reload();
            alert('Vendor deleted successfully!');
        },
        error: function () {
            alert('Error deleting vendor.');
        }
    });
}
</script>
@endpush

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .card {
        border-radius: 12px;
    }
    .card-header {
        border-top-left-radius: 12px !important;
        border-top-right-radius: 12px !important;
    }
    .btn-group .btn {
        border-radius: 20px !important;
        margin-left: 5px;
    }
    .table th, .table td {
        vertical-align: middle !important;
    }
    #vendorsTable_wrapper .dataTables_paginate .paginate_button {
        padding: 0.4rem 0.8rem;
    }
</style>
@endpush
