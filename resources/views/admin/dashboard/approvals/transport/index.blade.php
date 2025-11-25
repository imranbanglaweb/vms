@extends('admin.dashboard.master')

@section('main_content')
<section role="main" class="content-body" style="background-color:#f1f4f8;">
<div class="container-fluid px-4 mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">
            <i class="fa-solid fa-truck-moving me-2"></i>Transport Approval Panel
        </h3>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-4">

            @if($requisitions->isEmpty())
                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486802.png" width="120">
                    <h5 class="mt-3 text-muted">No pending requisitions for transport approval.</h5>
                </div>
            @else
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Req No</th>
                        <th>Requester</th>
                        <th>Department</th>
                        <th>Passengers</th>
                        <th>Dept Approved</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($requisitions as $req)
                    <tr>
                        <td>
                            <span class="fw-bold">{{ $req->requisition_number }}</span>
                        </td>

                        <td>
                            <i class="fa fa-user text-primary me-1"></i> 
                            {{ $req->requestedBy->name }}
                        </td>

                        <td>{{ $req->department->name }}</td>

                        <td>
                            <span class="badge bg-info">
                                {{ $req->number_of_passenger }} Persons
                            </span>
                        </td>

                        <td>
                            <span class="badge bg-success">{{ date('d M, Y', strtotime($req->department_approved_at)) }}</span>
                        </td>

                        <td>
                            <span class="badge bg-warning text-dark">Pending</span>
                        </td>

                        <td class="text-end">
                            <a href="{{ route('transport.approvals.show', $req->id) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="fa fa-eye me-1"></i>Process
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

        </div>
    </div>

</div>
</section>
@endsection
