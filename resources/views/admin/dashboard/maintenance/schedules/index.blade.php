@extends('admin.dashboard.master')

@section('main_content')
<section role="main" class="content-body">
<div class="container-fluid mt-4">
<br>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold"><i class="fa fa-tools me-2"></i> Maintenance Schedules</h4>
        <a href="{{ route('maintenance.schedules.create') }}" class="btn btn-primary btn-sm">
            <i class="fa fa-plus"></i> Add New
        </a>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-3 border-0">
        <div class="card-body">
            <form method="GET" action="">
                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Vehicle</label>
                        <select name="vehicle_id" class="form-control select2">
                            <option value="">All Vehicles</option>
                            @foreach($vehicles as $v)
                                <option value="{{ $v->id }}">{{ $v->vehicle_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Maintenance Type</label>
                        <select name="maintenance_type_id" class="form-control select2">
                            <option value="">All Types</option>
                            @foreach($types as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="active" class="form-control">
                            <option value="">All</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-dark w-100"><i class="fa fa-search"></i> Filter</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Vehicle</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Scheduled At</th>
                        <th>Next Due</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $key=>$s)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $s->vehicle->vehicle_name }}</td>
                            <td>{{ $s->title }}</td>
                            <td>{{ $s->type->name }}</td>
                            <td>{{ $s->scheduled_at }}</td>
                            <td>{{ $s->next_due_date ?? '-' }}</td>
                            <td>
                                @if($s->active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('maintenance-schedules.show', $s->id) }}" class="btn btn-info btn-sm">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('maintenance-schedules.edit', $s->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('maintenance-schedules.destroy', $s->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
</section>
@endsection
