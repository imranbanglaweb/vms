<tr>
    <td>{{ $driver->id }}</td>
    <td>{{ $driver->driver_name }}</td>
    <td>{{ $driver->license_number }}</td>
    <td>{{ $driver->mobile }}</td>
    <td>{{ $driver->department->name ?? '' }}</td>
    <td>{{ $driver->unit->name ?? '' }}</td>
</tr>
