@extends('admin.dashboard.master')

@section('main_content')
<section role="main" class="content-body" style="background:#f8f9fa;">

<div class="container mt-5">

    <h3 class="fw-bold mb-4 text-primary">
        <i class="fa fa-route me-2"></i> Trip Sheet Overview
    </h3>

    <div class="card shadow border-0">
        <div class="card-body">

            <table class="table table-hover align-middle fs-6">
                <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Requisition No</th>
                    <th>Vehicle</th>
                    <th>Driver</th>
                    <th>Status</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Action</th>
                </tr>
                </thead>

                <tbody>
                    @foreach($trips as $trip)
                    <tr>
                        <td>{{ $trip->id }}</td>
                        <td>{{ $trip->requisition->requisition_number }}</td>
                        <td>{{ $trip->vehicle->vehicle_name ?? '' }}</td>
                        <td>{{ $trip->driver->driver_name ?? '' }}</td>
                        <td>
                            <span class="badge 
                                {{ $trip->status == 'finished' ? 'bg-success' : 'bg-warning' }}">
                                {{ ucfirst($trip->status) }}
                            </span>
                        </td>
                        <td>{{ $trip->trip_start_time }}</td>
                        <td>{{ $trip->trip_end_time ?? '—' }}</td>
                        <td>
                            <a href="{{ route('trip-sheets.show', $trip->id) }}"
                               class="btn btn-sm btn-primary">
                                View
                            </a>
                             <!-- END TRIP BUTTON -->
                @if($trip->status == 'in_progress')
                <a href="{{ url('transport/trip-sheet/end/'.$trip->id) }}" 
                   class="btn btn-warning btn-sm">
                    End Trip
                </a>
                @endif
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
