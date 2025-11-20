@extends('admin.dashboard.master')

@section('main_content')

<style>
    body {
        background: #f8f9fa !important;
    }

    .form-label {
        font-size: 1.7rem;
        font-weight: 600;
    }

    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
    }

    .status-badge {
        font-size: 1.4rem;
        padding: 8px 16px;
        border-radius: 25px;
    }

    .detail-item {
        border-bottom: 1px solid #e9ecef;
        padding: 15px 0;
        margin: 0;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-right: 15px;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -23px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #007bff;
        border: 2px solid white;
    }
</style>

<section role="main" class="content-body">

<div class="container-fluid p-4">

    <!-- Header Card -->
    <div class="card info-card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="text-white mb-1">
                        <i class="fa fa-clipboard-list me-2"></i>
                        Requisition Details
                    </h2>
                    <p class="text-white mb-0 opacity-75">
                        Complete information for requisition #{{ $requisition->requisition_number }}
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    @if($requisition->status == 0)
                        <span class="status-badge bg-warning">
                            <i class="fa fa-clock me-1"></i> Pending
                        </span>
                    @elseif($requisition->status == 1)
                        <span class="status-badge bg-success">
                            <i class="fa fa-check-circle me-1"></i> Approved
                        </span>
                    @elseif($requisition->status == 2)
                        <span class="status-badge bg-danger">
                            <i class="fa fa-times-circle me-1"></i> Rejected
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Main Details -->
        <div class="col-lg-8">
            <!-- Basic Information Card -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-info-circle me-2"></i>Basic Information
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 detail-item">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-primary text-white">
                                    <i class="fa fa-hashtag"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-muted">Requisition Number</h6>
                                    <h5 class="mb-0 text-dark">{{ $requisition->requisition_number }}</h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 detail-item">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-success text-white">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-muted">Requisition Date</h6>
                                    <h5 class="mb-0 text-dark">
                                        {{ $requisition->requisition_date ? \Carbon\Carbon::parse($requisition->requisition_date)->format('M d, Y') : 'N/A' }}
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 detail-item">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-info text-white">
                                    <i class="fa fa-user-tie"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-muted">Requested By</h6>
                                    <h5 class="mb-0 text-dark">{{ $requisition->employee->name ?? 'N/A' }}</h5>
                                    <small class="text-muted">{{ $requisition->employee->email ?? '' }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 detail-item">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-warning text-white">
                                    <i class="fa fa-building"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-muted">Department</h6>
                                    <h5 class="mb-0 text-dark">{{ $requisition->department->name ?? 'N/A' }}</h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 detail-item">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-secondary text-white">
                                    <i class="fa fa-sitemap"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-muted">Unit</h6>
                                    <h5 class="mb-0 text-dark">{{ $requisition->unit->name ?? 'N/A' }}</h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 detail-item">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-dark text-white">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-muted">Number of Passengers</h6>
                                    <h5 class="mb-0 text-dark">{{ $requisition->number_of_passenger }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Travel Details Card -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-route me-2"></i>Travel Details
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 detail-item">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-success text-white">
                                    <i class="fa fa-location-dot"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-muted">From Location</h6>
                                    <h5 class="mb-0 text-dark">{{ $requisition->from_location }}</h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 detail-item">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-danger text-white">
                                    <i class="fa fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-muted">To Location</h6>
                                    <h5 class="mb-0 text-dark">{{ $requisition->to_location }}</h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 detail-item">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-info text-white">
                                    <i class="fa fa-clock"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-muted">Travel Date & Time</h6>
                                    <h5 class="mb-0 text-dark">
                                        {{ $requisition->travel_date ? \Carbon\Carbon::parse($requisition->travel_date)->format('M d, Y h:i A') : 'N/A' }}
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 detail-item">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-warning text-white">
                                    <i class="fa fa-calendar-check"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-muted">Return Date & Time</h6>
                                    <h5 class="mb-0 text-dark">
                                        {{ $requisition->return_date ? \Carbon\Carbon::parse($requisition->return_date)->format('M d, Y h:i A') : 'N/A' }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Purpose -->
                    <div class="detail-item mt-3">
                        <div class="d-flex align-items-start">
                            <div class="icon-circle bg-primary text-white">
                                <i class="fa fa-list"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-2 text-muted">Purpose</h6>
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-0 text-dark" style="font-size: 1.6rem; line-height: 1.6;">
                                        {{ $requisition->purpose }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Passengers Card -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-users me-2"></i>Passengers
                        <span class="badge bg-white text-info ms-2">{{ count($requisition->passengers) }}</span>
                    </h4>
                </div>
                <div class="card-body">
                    @if($requisition->passengers && count($requisition->passengers) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Employee Name</th>
                                        <th>Department</th>
                                        <th>Unit</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requisition->passengers as $index => $passenger)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle-sm bg-primary text-white me-2">
                                                        {{ substr($passenger->employee->name ?? 'N/A', 0, 1) }}
                                                    </div>
                                                    {{ $passenger->employee->name ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>{{ $passenger->employee->department->name ?? 'N/A' }}</td>
                                            <td>{{ $passenger->employee->unit->name ?? 'N/A' }}</td>
                                            <td>{{ $passenger->employee->email ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fa fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Passengers Added</h5>
                            <p class="text-muted">This requisition doesn't have any additional passengers.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="col-lg-4">
            <!-- Vehicle & Driver Card -->
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-car me-2"></i>Vehicle & Driver
                    </h4>
                </div>
                <div class="card-body">
                    <div class="detail-item">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-warning text-white">
                                <i class="fa fa-car-side"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-muted">Vehicle</h6>
                                <h5 class="mb-0 text-dark">{{ $requisition->vehicle->vehicle_name ?? 'N/A' }}</h5>
                                @if($requisition->vehicle)
                                    <small class="text-muted">
                                        Model: {{ $requisition->vehicle->model ?? 'N/A' }} | 
                                        Plate: {{ $requisition->vehicle->license_plate ?? 'N/A' }}
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-info text-white">
                                <i class="fa fa-user"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-muted">Driver</h6>
                                <h5 class="mb-0 text-dark">{{ $requisition->driver->driver_name ?? 'N/A' }}</h5>
                                @if($requisition->driver)
                                    <small class="text-muted">
                                        License: {{ $requisition->driver->license_number ?? 'N/A' }} | 
                                        Phone: {{ $requisition->driver->phone ?? 'N/A' }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Timeline Card -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-history me-2"></i>Status Timeline
                    </h4>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <!-- Created -->
                        <div class="timeline-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fa fa-plus-circle text-success"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Requisition Created</h6>
                                    <p class="mb-1 text-muted">
                                        {{ $requisition->created_at->format('M d, Y h:i A') }}
                                    </p>
                                    <small class="text-muted">By: {{ $requisition->employee->name ?? 'System' }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Status Updates -->
                        @if($requisition->status == 1 && $requisition->approved_at)
                            <div class="timeline-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fa fa-check-circle text-success"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Approved</h6>
                                        <p class="mb-1 text-muted">
                                            {{ \Carbon\Carbon::parse($requisition->approved_at)->format('M d, Y h:i A') }}
                                        </p>
                                        @if($requisition->approvedBy)
                                            <small class="text-muted">By: {{ $requisition->approvedBy->name }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($requisition->status == 2 && $requisition->rejected_at)
                            <div class="timeline-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fa fa-times-circle text-danger"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Rejected</h6>
                                        <p class="mb-1 text-muted">
                                            {{ \Carbon\Carbon::parse($requisition->rejected_at)->format('M d, Y h:i A') }}
                                        </p>
                                        @if($requisition->rejectedBy)
                                            <small class="text-muted">By: {{ $requisition->rejectedBy->name }}</small>
                                        @endif
                                        @if($requisition->rejection_reason)
                                            <div class="alert alert-danger mt-2 p-2">
                                                <small><strong>Reason:</strong> {{ $requisition->rejection_reason }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Last Updated -->
                        @if($requisition->updated_at != $requisition->created_at)
                            <div class="timeline-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fa fa-edit text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Last Updated</h6>
                                        <p class="mb-1 text-muted">
                                            {{ $requisition->updated_at->format('M d, Y h:i A') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-cogs me-2"></i>Actions
                    </h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('requisitions.index') }}" class="btn btn-secondary btn-lg">
                            <i class="fa fa-arrow-left me-2"></i>Back to List
                        </a>

                        @if($requisition->status == 0)
                            <a href="{{ route('requisitions.edit', $requisition->id) }}" class="btn btn-warning btn-lg">
                                <i class="fa fa-edit me-2"></i>Edit Requisition
                            </a>
                        @endif

                        <!-- Print Button -->
                        <button onclick="window.print()" class="btn btn-info btn-lg">
                            <i class="fa fa-print me-2"></i>Print Details
                        </button>

                        <!-- Download as PDF -->
                        <a href="{{ route('requisitions.download', $requisition->id) }}" class="btn btn-danger btn-lg">
                            <i class="fa fa-file-pdf me-2"></i>Download PDF
                        </a>

                        @if($requisition->status == 0)
                            <form action="{{ route('requisitions.destroy', $requisition->id) }}" method="POST" class="d-grid">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-lg" 
                                        onclick="return confirm('Are you sure you want to delete this requisition?')">
                                    <i class="fa fa-trash me-2"></i>Delete Requisition
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-chart-bar me-2"></i>Quick Stats
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <h3 class="text-primary mb-1">{{ $requisition->number_of_passenger }}</h3>
                                <small class="text-muted">Total Passengers</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div>
                                <h3 class="text-success mb-1">{{ count($requisition->passengers) }}</h3>
                                <small class="text-muted">Additional Passengers</small>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-12">
                            <div class="bg-light p-2 rounded">
                                <small class="text-muted">Created {{ $requisition->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</section>

<!-- Print Styles -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .content-body, .content-body * {
            visibility: visible;
        }
        .content-body {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .card {
            border: 1px solid #000 !important;
            box-shadow: none !important;
        }
        .btn, .actions-card {
            display: none !important;
        }
    }
</style>

@endsection

@push('scripts')
<script>
// Add some interactive features
$(document).ready(function() {
    // Add smooth scrolling for better UX
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 20
            }, 1000);
        }
    });

    // Add copy to clipboard for requisition number
    $('.copy-req-number').on('click', function() {
        var reqNumber = "{{ $requisition->requisition_number }}";
        navigator.clipboard.writeText(reqNumber).then(function() {
            var originalText = $(this).html();
            $(this).html('<i class="fa fa-check"></i> Copied!');
            var self = this;
            setTimeout(function() {
                $(self).html(originalText);
            }, 2000);
        }.bind(this));
    });
});
</script>
@endpush