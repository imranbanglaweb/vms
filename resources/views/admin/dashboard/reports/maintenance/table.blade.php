<table class="table table-hover align-middle">
<thead class="table-light">
<tr>
    <th>#</th>
    <th>Vehicle</th>
    <th>Type</th>
    <th>Vendor</th>
    <th>Date</th>
    <th>Cost</th>
    <th>Status</th>
</tr>
</thead>
<tbody>
@forelse($records as $r)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $r->vehicle->vehicle_name }}</td>
    <td>{{ $r->type->name }}</td>
    <td>{{ $r->vendor->name ?? 'N/A' }}</td>
    <td>{{ $r->maintenance_date }}</td>
    <td>{{ number_format($r->cost,2) }}</td>
    <td>
        <span class="badge bg-{{ $r->status == 'Completed' ? 'success':'warning' }}">
            {{ $r->status }}
        </span>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center text-muted">No records found</td>
</tr>
@endforelse
</tbody>
</table>
