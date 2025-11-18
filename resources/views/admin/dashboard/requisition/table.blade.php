@foreach($requisitions as $key => $req)
<tr>
    <td>{{ $requisitions->firstItem() + $key }}</td>

    <td>{{ $req->requestedBy->name ?? 'N/A' }}</td>

    <!-- <td>{{ $req->requestedBy->department_name ?? 'N/A' }}</td> -->
    <td>{{ $req->requestedBy->department->department_name ?? 'N/A' }}</td>

    <td>{{ $req->vehicle->vehicle_name ?? 'Not Assigned' }}</td>

    <td>{{ $req->driver->driver_name ?? 'Not Assigned' }}</td>

    <td>{{ $req->travel_date ? $req->travel_date->format('d M, Y h:i A') : 'N/A' }}</td>

    <td>{{ Str::limit($req->purpose, 25) }}</td>

    <td>
        <span class="badge 
            @if($req->status == 'Pending') bg-warning 
            @elseif($req->status == 'Approved') bg-success 
            @elseif($req->status == 'Rejected') bg-danger
            @else bg-secondary @endif
        ">
            {{ $req->status }}
        </span>
    </td>

    <td width="">
        <a href="{{ route('requisitions.show', $req->id) }}" 
           class="btn btn-sm btn-info">
            <i class="fa fa-eye"></i>
        </a>

        <a href="{{ route('requisitions.edit', $req->id) }}" 
           class="btn btn-sm btn-primary">
            <i class="fa fa-edit"></i>
        </a>

        <!-- <form action="{{ route('requisitions.destroy', $req->id) }}" 
              method="POST" 
              class="d-inline"
              onsubmit="return confirm('Are you sure?');">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger">
                <i class="fa fa-minus"></i>
            </button>
        </form> -->
    </td>
</tr>
@endforeach

@if($requisitions->count() == 0)
<tr>
    <td colspan="9" class="text-center text-muted py-3">
        No requisitions found.
    </td>
</tr>
@endif
