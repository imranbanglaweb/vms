@extends('admin.dashboard.master')

@section('main_content')
<br>
<br>
<br>
<section role="main" class="content-body">
<div class="container-fluid mt-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Requisition List</h3>
        <div>
            <a href="{{ route('requisitions.export.excel') }}" class="btn btn-success btn-sm">Export Excel</a>
            <a href="{{ route('requisitions.export.pdf') }}" class="btn btn-danger btn-sm">Export PDF</a>
            <a href="{{ route('requisitions.create') }}" class="btn btn-primary btn-sm">+ New Requisition</a>
        </div>
    </div>

    <!-- Search Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('requisitions.index') }}">
                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="fw-semibold">Employee</label>
                        <select name="employee_id" class="form-select">
                            <option value="">All Employees</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="fw-semibold">Department</label>
                        <select name="department_id" class="form-select">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="fw-semibold">From Date</label>
                        <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="fw-semibold">To Date</label>
                        <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            @foreach(['Pending','Approved','Rejected','Completed'] as $st)
                                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                                    {{ $st }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="mt-3">
                    <button class="btn btn-dark btn-sm">Search</button>
                    <a href="{{ route('requisitions.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                </div>

            </form>
        </div>
    </div>

    <!-- Requisition Table -->
    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Requested By</th>
                        <th>Department</th>
                        <th>Vehicle</th>
                        <th>Driver</th>
                        <th>From–To</th>
                        <th>Travel Date</th>
                        <th>Status</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($requisitions as $req)
                    <tr>
                        <td>{{ $req->id }}</td>
                        <td>{{ $req->requestedBy->name ?? '' }}</td>
                        <td>{{ $req->requestedBy->department->name ?? '' }}</td>
                        <td>{{ $req->vehicle->vehicle_name ?? '-' }}</td>
                        <td>{{ $req->driver->name ?? '-' }}</td>

                        <td>
                            <strong>{{ $req->from_location }}</strong> → 
                            {{ $req->to_location }}
                        </td>

                        <td>{{ date('d M, Y h:i A', strtotime($req->travel_date)) }}</td>

                        <td>
                            <span class="badge 
                                @if($req->status=='Pending') bg-warning 
                                @elseif($req->status=='Approved') bg-success
                                @elseif($req->status=='Rejected') bg-danger
                                @else bg-primary @endif">
                                {{ $req->status }}
                            </span>
                        </td>

                        <td>
                            <a href="{{ route('requisitions.show',$req->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('requisitions.edit',$req->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('requisitions.destroy',$req->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Delete?')" class="btn btn-danger btn-sm">Del</button>
                            </form>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-3 text-muted">No Requisitions Found</td>
                    </tr>
                    @endforelse

                </tbody>
            </table>

            <div class="mt-3">
                {{ $requisitions->links() }}
            </div>

        </div>
    </div>

</div>
</section>
@endsection
