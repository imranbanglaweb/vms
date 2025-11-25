@extends('admin.dashboard.master')

@section('main_content')
<section role="main" class="content-body" style="background-color:#f1f4f8;">
<div class="container-fluid mt-4">

    <h3 class="mb-4 fw-bold"><i class="fa fa-tasks me-2"></i> Department Approval Panel</h3>

    <div class="card shadow-lg border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>Req No</th>
                            <th><i class="fa fa-user"></i> Requested By</th>
                            <th><i class="fa fa-building"></i> Department</th>
                            <th><i class="fa fa-layer-group"></i> Unit</th>
                            <th><i class="fa fa-users"></i> Passengers</th>
                            <th><i class="fa fa-calendar-alt"></i> Created At</th>
                            <th class="text-center"><i class="fa fa-cogs"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requisitions as $req)
                        <tr>
                            <td class="fw-bold">{{ $req->requisition_number }}</td>
                            <td>{{ $req->requestedBy->name ?? 'N/A' }}</td>
                            <td><span class="badge bg-info text-dark">{{ $req->department->department_name ?? '-' }}</span></td>
                            <td><span class="badge bg-secondary">{{ $req->unit->unit_name ?? '-' }}</span></td>
                            <td><span class="badge bg-success">{{ $req->number_of_passenger }}</span></td>
                            <td>{{ $req->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <!-- <button class="btn btn-outline-primary btn-sm reviewBtn" data-id="{{ $req->id }}">
                                    <i class="fa fa-eye"></i>
                                </button> -->
                                  <a href="{{ route('department.approvals.show', $req->id) }}" 
                                   class="btn btn-primary btn-sm">
                                    Review
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fa fa-file-alt me-2"></i> Requisition Details</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-3" id="modalBody">
          <div class="text-center py-5">
              <div class="spinner-border text-primary"></div>
              <p class="mt-2">Loading details...</p>
          </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success approveBtn" data-id="">Approve</button>
        <button class="btn btn-danger rejectBtn" data-id="">Reject</button>
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</section>

<!-- jQuery + Bootstrap -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function(){

    // Initialize modal object
    var reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));

    // Click Review button
    $('.reviewBtn').click(function(){
        let id = $(this).data('id');

        // Show modal immediately with loader
        $('#modalBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary"></div>
                <p class="mt-2">Loading details...</p>
            </div>
        `);
        reviewModal.show();

        // Load content via AJAX
        $.get("{{ url('department/approvals') }}/" + id, function(data){
            $('#modalBody').html(data);

            // Set data-id for approve/reject buttons dynamically
            $('.approveBtn, .rejectBtn').data('id', id);
        }).fail(function(){
            $('#modalBody').html('<p class="text-danger text-center py-5">Failed to load requisition details.</p>');
        });
    });

    // Approve button click
    $(document).on('click', '.approveBtn', function(){
        let id = $(this).data('id');
        let remarks = $('#remarks').val() || '';

        $.post("{{ url('department/approvals') }}/" + id + "/approve", {
            _token: '{{ csrf_token() }}',
            remarks: remarks
        }, function(res){
            alert(res.message);
            reviewModal.hide();
            location.reload();
        }).fail(function(){
            alert('Failed to approve requisition.');
        });
    });

    // Reject button click
    $(document).on('click', '.rejectBtn', function(){
        let id = $(this).data('id');
        let remarks = $('#remarks').val() || '';

        if(!remarks.trim()){
            alert('Remarks are required to reject.');
            return;
        }

        $.post("{{ url('department/approvals') }}/" + id + "/reject", {
            _token: '{{ csrf_token() }}',
            remarks: remarks
        }, function(res){
            alert(res.message);
            reviewModal.hide();
            location.reload();
        }).fail(function(){
            alert('Failed to reject requisition.');
        });
    });

});
</script>
@endsection
