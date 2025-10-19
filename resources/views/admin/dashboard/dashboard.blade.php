@extends('admin.dashboard.master')
@section('title', 'Dashboard')

@section('main_content')
<section role="main" class="content-body">
    <div class="container-fluid">
        <!-- Header with Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="page-title mb-1">Welcome, {{ Auth::user()->name }}</h4>
                        <p class="text-muted">Here's what's happening with your documents today.</p>
                    </div>
              {{--       <div class="quick-actions">
                        <a href="{{ route('documents.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> New Document
                        </a>
                    </div> --}}
						</div>
													</div>
												</div>

        <!-- Stats Cards -->
        <div class="row">
            <!-- Total Documents -->
            <div class="col-xl-3 col-md-4">
                <div class="stat-card">
                    <div class="stat-card__icon-wrapper bg-primary">
                        <i class="fa fa-file"></i>
                    </div>
                    <div class="stat-card__content">
                        <h3 class="stat-card__number">{{ number_format($totalDocuments) }}</h3>
                        <p class="stat-card__title">Total Documents</p>
														</div>
														</div>
													</div>

            <!-- Pending Documents -->
            <div class="col-xl-3 col-md-4">
                <div class="stat-card">
                    <div class="stat-card__icon-wrapper bg-warning">
                        <i class="fa fa-times-circle"></i>
                        @if($pendingDocuments >= 0)
                            <span class="pulse-ring"></span>
                        @endif
                    </div>
                    <div class="stat-card__content">
                        <h3 class="stat-card__number">{{ number_format($pendingDocuments) }}</h3>
                        <p class="stat-card__title">Pending Documents</p>
													</div>
												</div>
											</div>

            <!-- Returned Documents -->
            <div class="col-xl-3 col-md-4">
                <div class="stat-card">
                    <div class="stat-card__icon-wrapper bg-info">
                        <i class="fa fa-undo"></i>
										</div>
                    <div class="stat-card__content">
                        <h3 class="stat-card__number">{{ number_format($returnedDocuments) }}</h3>
                        <p class="stat-card__title">Returned Documents</p>
								</div>
													</div>
												</div>

       
														</div>

        <!-- Approval Stats (Show only for super-admin) -->
        @if(auth()->user()->hasRole('Super Admin'))
        <div class="row mt-4">
                 <!-- Withdrawn Documents -->
            <div class="col-xl-3 col-md-4">
                <div class="stat-card">
                    <div class="stat-card__icon-wrapper bg-info">
                        <i class="fa fa-file"></i>
													</div>
                    <div class="stat-card__content">
                        <h3 class="stat-card__number">{{ number_format($withdrawnDocuments) }}</h3>
                        <p class="stat-card__title">Withdrawn Documents</p>
													</div>
												</div>
											</div>
            <!-- Pending Approvals -->
            <div class="col-xl-4 col-md-4">
                <div class="stat-card">
                    <div class="stat-card__icon-wrapper bg-danger">
                        <i class="fa fa-file"></i>
                        @if($pendingApprovals > 0)
                            <span class="pulse-ring"></span>
                        @endif
										</div>
                    <div class="stat-card__content">
                        <h3 class="stat-card__number">{{ number_format($pendingApprovals) }}</h3>
                        <p class="stat-card__title">Pending Approvals</p>
                     {{--    <a href="{{ route('documents.pending-approval') }}" class="btn btn-sm btn-outline-danger mt-2">
                            Review Now
                        </a> --}}
								</div>
													</div>
												</div>

            <!-- Approved Documents -->
            <div class="col-xl-4 col-md-4">
                <div class="stat-card">
                    <div class="stat-card__icon-wrapper bg-success">
                        <i class="fa fa-check"></i>
														</div>
                    <div class="stat-card__content">
                        <h3 class="stat-card__number">{{ number_format($approvedDocuments) }}</h3>
                        <p class="stat-card__title">Approved Documents</p>
													</div>
												</div>
											</div>

            <!-- Rejected Documents -->
        {{--     <div class="col-xl-4 col-md-6">
                <div class="stat-card">
                    <div class="stat-card__icon-wrapper bg-secondary">
                        <i class="fa fa-minus"></i>
                    </div>
                    <div class="stat-card__content">
                        <h3 class="stat-card__number">
                        {{ number_format($rejectedDocuments) }}</h3>
                        <p class="stat-card__title">Rejected Documents</p>
                    </div>
										</div>
            </div> --}}
								</div>
        @endif

        <!-- Recent Documents Table -->
       {{--  <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Documents</h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="table_search" id="recentDocsSearch" 
                                    class="form-control float-right" placeholder="Search...">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-default">
                                        <i class="fa fa-search"></i>
                                    </button>
													</div>
												</div>
														</div>
													</div>
                    <div class="card-body">
                        <div id="recentDocumentsTable">
                            <!-- Table content will be loaded here -->
													</div>
												</div>
											</div>
										</div>
        </div> --}}

        <!-- Recent Pending Approvals Table -->
        @if(auth()->user()->hasRole('Super Admin'))
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Pending Approvals</h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="table_search" id="pendingApprovalsSearch" 
                                    class="form-control float-right" placeholder="Search...">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-default">
                                        <i class="fa fa-search"></i>
                                    </button>
								</div>
							</div>
						</div>
					</div>
                    <div class="card-body">
                        <div id="pendingApprovalsTable">
                            <!-- Table content will be loaded here -->
                        </div>
                    </div>
		    </div>
            </div>
        </div>
        @endif
    </div>
    </section>

@push('styles')
<style>
    /* Modern Dashboard Styles */
    .page-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
    }

    /* Stat Cards */
    .stat-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 0 20px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card__icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        position: relative;
    }

    .stat-card__icon-wrapper i {
        color: #fff;
        font-size: 24px;
    }

    .stat-card__number {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .stat-card__title {
        color: #6c757d;
        margin-bottom: 0;
    }

    .pulse-ring {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        animation: pulse 1.5s cubic-bezier(0.24, 0, 0.38, 1) infinite;
        border: 3px solid rgba(255, 255, 255, 0.4);
    }

    @keyframes pulse {
        0% {
            transform: scale(0.95);
            opacity: 0.8;
        }
        50% {
            transform: scale(1.05);
            opacity: 0.5;
        }
        100% {
            transform: scale(0.95);
            opacity: 0.8;
        }
    }

    /* Custom Table Styles */
    .custom-table {
        margin: 0;
    }

    .custom-table thead th {
        background: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 1rem;
        border-bottom: 2px solid #e9ecef;
    }

    .custom-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }

    /* Status Badges */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }

    .status-withdrawn {
        background: rgba(255,193,7,0.1);
        color: #ffc107;
    }

    .status-returned {
        background: rgba(40,167,69,0.1);
        color: #28a745;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .btn-icon:hover {
        background: #e9ecef;
    }

    /* Empty State */
    .empty-state {
        padding: 2rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 2rem;
        display: block;
    }

    .badge {
        padding: 0.5em 0.75em;
    }

    .btn-group .btn {
        margin-right: 2px;
    }

    .table td, .table th {
        vertical-align: middle;
    }

    /* Card Actions */
    .card-actions .btn {
        padding: 0.375rem 0.75rem;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem;
    }

    .dropdown-item i {
        width: 1.25rem;
    }

    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-body {
        padding: 1.25rem;
    }

    .icon {
        height: 48px;
        width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .opacity-50 {
        opacity: 0.5;
    }

    .card-footer {
        background: rgba(0, 0, 0, 0.1);
        border-top: none;
        padding: 0.75rem 1.25rem;
    }

    .stretched-link::after {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1;
        content: "";
    }

    .table th {
        border-top: none;
    }
</style>
@endpush

@push('scripts')
   <script>
// Load tables on page load
$(document).ready(function() {
    loadRecentDocuments();
    loadPendingApprovals();
    
    // Search functionality for recent documents
    let recentDocsTimer;
    $('#recentDocsSearch').on('keyup', function() {
        clearTimeout(recentDocsTimer);
        recentDocsTimer = setTimeout(() => {
            loadRecentDocuments($(this).val());
        }, 500);
    });
    
    // Search functionality for pending approvals
    let pendingApprovalsTimer;
    $('#pendingApprovalsSearch').on('keyup', function() {
        clearTimeout(pendingApprovalsTimer);
        pendingApprovalsTimer = setTimeout(() => {
            loadPendingApprovals($(this).val());
        }, 500);
    });
});

function loadRecentDocuments(search = '') {
    $.ajax({
        url: "{{ route('dashboard.recent-documents') }}",
        type: 'GET',
        data: { search: search },
        success: function(response) {
            $('#recentDocumentsTable').html(response);
        },
        error: function(xhr) {
            console.error('Error loading recent documents:', xhr);
        }
    });
}

function loadPendingApprovals(search = '') {
    $.ajax({
        url: "{{ route('dashboard.pending-approvals') }}",
        type: 'GET',
        data: { search: search },
        success: function(response) {
            $('#pendingApprovalsTable').html(response);
        },
        error: function(xhr) {
            console.error('Error loading pending approvals:', xhr);
        }
    });
}

function viewDocument(id) {
    $.ajax({
        url: "{{ route('documents.show', ':id') }}".replace(':id', id),
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#documentModal').remove();
                $('body').append(`<div class="modal fade" id="documentModal" 
                    tabindex="-1" role="dialog" aria-hidden="true">${response.view}</div>`);
                $('#documentModal').modal('show');
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr) {
            console.error('Error:', xhr);
            Swal.fire('Error', 'Could not load document details', 'error');
        }
    });
}

function editDocument(id) {
    $.ajax({
        url: "{{ route('documents.edit', ':id') }}".replace(':id', id),
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#documentModal').remove();
                $('body').append(`<div class="modal fade" id="documentModal" 
                    tabindex="-1" role="dialog" aria-hidden="true">${response.view}</div>`);
                $('#documentModal').modal('show');
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr) {
            console.error('Error:', xhr);
            Swal.fire('Error', 'Could not load edit form', 'error');
        }
    });
}

function approveDocument(id) {
    Swal.fire({
        title: 'Approve Document',
        text: 'Are you sure you want to approve this document?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, approve it!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('documents.approve', ':id') }}".replace(':id', id),
                type: 'POST',
            data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Success', response.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    Swal.fire('Error', 'Could not approve document', 'error');
                }
            });
        }
    });
}

function rejectDocument(id) {
    Swal.fire({
        title: 'Reject Document',
        text: 'Please provide a reason for rejection:',
        input: 'textarea',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Reject',
        cancelButtonText: 'Cancel',
        preConfirm: (reason) => {
            if (!reason) {
                Swal.showValidationMessage('Please enter a rejection reason');
            }
            return reason;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('documents.reject', ':id') }}".replace(':id', id),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    reason: result.value
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Success', response.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    Swal.fire('Error', 'Could not reject document', 'error');
                }
            });
        }
    });
}

// Refresh table data
function refreshTable() {
    location.reload();
}
    </script>
@endpush
@endsection