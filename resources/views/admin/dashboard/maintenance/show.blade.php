@extends('admin.dashboard.master')

@section('main_content')
<section role="main" class="content-body">

<div class="container-fluid mt-4">

    <!-- TITLE + BACK BUTTON -->
    <div class="d-flex justify-content-between mb-3">
        <h3 class="fw-bold">
            <i class="fa fa-file-alt me-2"></i> Requisition Details
        </h3>

        <a href="{{ route('requisitions.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h6 class="text-muted">Requisition No</h6>
                <h5>{{ $requisition->requisition_no }}</h5>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h6 class="text-muted">Vehicle</h6>
                <h5>{{ $requisition->vehicle->vehicle_no ?? '-' }}</h5>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h6 class="text-muted">Employee</h6>
                <h5>{{ $requisition->employee->name ?? '-' }}</h5>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h6 class="text-muted">Status</h6>
                <h5>
                    @if($requisition->status == 'Approved')
                        <span class="badge bg-success">Approved</span>
                    @elseif($requisition->status == 'Rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @else
                        <span class="badge bg-warning text-dark">Pending</span>
                    @endif
                </h5>
            </div>
        </div>
    </div>

    <!-- DETAILS -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3"><i class="fa fa-info-circle me-2"></i> Details</h5>

            <div class="row">
                <div class="col-md-4">
                    <p><strong>Type:</strong> {{ $requisition->requisition_type }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Priority:</strong> {{ $requisition->priority }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Date:</strong> {{ $requisition->date }}</p>
                </div>
                <div class="col-md-12">
                    <p><strong>Description:</strong> {{ $requisition->description ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ITEMS TABLE -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h5 class="fw-bold mb-3"><i class="fa fa-list me-2"></i> Items</h5>

            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Item Name</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requisition->items as $index => $item)
                        <tr data-item='@json($item)' class="itemRow" style="cursor:pointer;">
                            <td>{{ $index+1 }}</td>
                            <td>{{ $item->category->name ?? '-' }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ number_format($item->unit_price,2) }}</td>
                            <td>{{ number_format($item->total,2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-info viewItemBtn">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- TOTAL COST -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3"><i class="fa fa-dollar-sign me-2"></i> Cost Summary</h5>

            <h4 class="text-success">Grand Total: 
                ${{ number_format($requisition->grand_total,2) }}
            </h4>
        </div>
    </div>

    <!-- APPROVE / REJECT BUTTONS -->
    <div class="text-end mb-5">
        <button class="btn btn-success me-2" id="approveBtn"><i class="fa fa-check"></i> Approve</button>
        <button class="btn btn-danger" id="rejectBtn"><i class="fa fa-times"></i> Reject</button>
    </div>

</div>

<!-- ITEM DETAILS MODAL -->
<div class="modal fade" id="itemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Item Details</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="modalContent">
                <!-- Filled Dynamically -->
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

</section>
@endsection

@section('scripts')
<script>
$(document).ready(function () {

    // Open item modal on row click or button click
    $('.viewItemBtn, .itemRow').on('click', function () {

        let item = $(this).closest('tr').data('item');

        let html = `
            <table class="table table-bordered">
                <tr><th>Category</th><td>${item.category?.name ?? '-'}</td></tr>
                <tr><th>Item Name</th><td>${item.item_name}</td></tr>
                <tr><th>Quantity</th><td>${item.qty}</td></tr>
                <tr><th>Unit Price</th><td>$${item.unit_price}</td></tr>
                <tr><th>Total</th><td>$${item.total}</td></tr>
                <tr><th>Description</th><td>${item.description ?? '-'}</td></tr>
            </table>
        `;

        $('#modalContent').html(html);
        $('#itemModal').modal('show');
    });


    // Approve
    $('#approveBtn').click(function () {
        updateStatus("Approved");
    });

    // Reject
    $('#rejectBtn').click(function () {
        updateStatus("Rejected");
    });

    function updateStatus(status) {
        Swal.fire({
            title: `Confirm ${status}?`,
            icon: "warning",
            showCancelButton: true,
        }).then((res) => {
            if (res.isConfirmed) {
                $.ajax({
                    url: "{{ route('requisitions.updateStatus', $requisition->id) }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: status
                    },
                    success: function (data) {
                        Swal.fire("Success!", data.message, "success");
                        location.reload();
                    }
                });
            }
        });
    }

});
</script>
@endsection
