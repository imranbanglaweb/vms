@extends('admin.dashboard.master')

@section('main_content')
<section role="main" class="content-body">

<div class="container-fluid p-4">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <h4 class="m-0"><i class="fa fa-edit me-2"></i>Edit Requisition</h4>
            <a href="{{ route('requisitions.index') }}" class="btn btn-light btn-sm">Back</a>
        </div>

        <div class="card-body">

            <form id="editForm" method="POST" action="{{ route('requisitions.update', $requisition->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <!-- Requested By -->
                    <div class="col-md-4">
                        <label>Requested By <span class="text-danger">*</span></label>
                        <select class="form-control" name="requested_by" required>
                            <option value="">Select Employee</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}"
                                    {{ $requisition->requested_by == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }} ({{ $emp->department }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Vehicle -->
                    <div class="col-md-4">
                        <label>Vehicle <span class="text-danger">*</span></label>
                        <select class="form-control" name="vehicle_id" required>
                            <option value="">Select Vehicle</option>
                            @foreach ($vehicles as $veh)
                                <option value="{{ $veh->id }}"
                                    {{ $requisition->vehicle_id == $veh->id ? 'selected' : '' }}>
                                    {{ $veh->vehicle_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Driver -->
                    <div class="col-md-4">
                        <label>Driver <span class="text-danger">*</span></label>
                        <select class="form-control" name="driver_id" required>
                            <option value="">Select Driver</option>
                            @foreach ($drivers as $driver)
                                <option value="{{ $driver->id }}"
                                    {{ $requisition->driver_id == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->driver_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- From -->
                    <div class="col-md-6">
                        <label>From Location <span class="text-danger">*</span></label>
                        <input type="text" name="from_location" value="{{ $requisition->from_location }}"
                               class="form-control" required>
                    </div>

                    <!-- To -->
                    <div class="col-md-6">
                        <label>To Location <span class="text-danger">*</span></label>
                        <input type="text" name="to_location" value="{{ $requisition->to_location }}"
                               class="form-control" required>
                    </div>

                    <!-- Travel Date -->
                    <div class="col-md-6">
                        <label>Travel Date <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="travel_date"
                               value="{{ $requisition->travel_date }}"
                               class="form-control" required>
                    </div>

                    <!-- Return Date -->
                    <div class="col-md-6">
                        <label>Return Date</label>
                        <input type="datetime-local" name="return_date"
                               value="{{ $requisition->return_date }}"
                               class="form-control">
                    </div>

                    <div class="col-md-12">
                        <label>Purpose</label>
                        <textarea name="purpose" class="form-control" rows="3">{{ $requisition->purpose }}</textarea>
                    </div>

                    <div class="col-md-12 mt-3 text-end">
                        <button class="btn btn-primary">
                            <i class="fa fa-save"></i> Update Requisition
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

</div>

</section>
@endsection
