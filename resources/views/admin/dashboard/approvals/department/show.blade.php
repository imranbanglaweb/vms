@extends('admin.dashboard.master')

@section('main_content')
<section role="main" class="content-body" style="background-color:#f1f4f8;">

    <h3>Review Requisition</h3>

    <div class="card shadow-lg mt-3">
        <div class="card-body">

            <h5>Requisition Information</h5>
            <hr>

            <p><strong>Requisition No:</strong> {{ $requisition->requisition_number }}</p>
            <p><strong>Requested By:</strong> {{ $requisition->requestedBy->name }}</p>
            <p><strong>Department:</strong> {{ $requisition->department->name }}</p>
            <p><strong>Unit:</strong> {{ $requisition->unit->name }}</p>
            <p><strong>Passengers:</strong> {{ $requisition->number_of_passenger }}</p>

            <form id="actionForm" class="mt-3">
                @csrf

                <!-- <label>Remarks</label>
                <textarea name="remarks" class="form-control"></textarea> -->

                <div class="mt-3">
                    <button type="button" class="btn btn-success" onclick="submitApproval('approve')">
                        Approve
                    </button>

                    <button type="button" class="btn btn-danger" onclick="submitApproval('reject')">
                        Reject
                    </button>
                </div>
            </form>

        </div>
    </div>

</section>

<script>
function submitApproval(action) {
    let url = action === 'approve'
        ? '{{ route("department.approvals.approve", $requisition->id) }}'
        : '{{ route("department.approvals.reject", $requisition->id) }}';

    $.ajax({
        url: url,
        method: "POST",
        data: $("#actionForm").serialize(),
        success: function(res) {
            alert(res.message);
            window.location.href = "{{ route('department.approvals.index') }}";
        }
    });
}
</script>

@endsection
