@extends('admin.dashboard.master')

@section('main_content')
<section role="main" class="content-body">
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-primary mb-0">
            <i class="fa fa-edit"></i> Edit Requisition
        </h3>
        <a href="{{ route('requisition.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3 mx-auto position-relative" style="max-width: 1000px;">
        <div id="formPreloader" class="preloader-overlay d-none">
            <div class="spinner-border text-primary" role="status"></div>
        </div>

        <div class="card-body bg-light p-4">
            <form id="requisitionForm" action="{{ route('requisition.update', $requisition->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">From Location *</label>
                        <input type="text" name="from_location" class="form-control" 
                            value="{{ $requisition->from_location }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">To Location *</label>
                        <input type="text" name="to_location" class="form-control" 
                            value="{{ $requisition->to_location }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Travel Date *</label>
                        <input type="datetime-local" name="travel_date" class="form-control" 
                            value="{{ \Carbon\Carbon::parse($requisition->travel_date)->format('Y-m-d\TH:i') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Return Date</label>
                        <input type="datetime-local" name="return_date" class="form-control"
                            value="{{ $requisition->return_date ? \Carbon\Carbon::parse($requisition->return_date)->format('Y-m-d\TH:i') : '' }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Vehicle</label>
                        <select name="vehicle_id" class="form-select">
                            <option value="">Select Vehicle</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ $requisition->vehicle_id == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Driver</label>
                        <select name="driver_id" class="form-select">
                            <option value="">Select Driver</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ $requisition->driver_id == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['Pending', 'Approved', 'Rejected', 'Completed'] as $st)
                                <option value="{{ $st }}" {{ $requisition->status == $st ? 'selected' : '' }}>
                                    {{ $st }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Purpose</label>
                        <textarea name="purpose" class="form-control" rows="3">{{ $requisition->purpose }}</textarea>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5 py-2 fs-6">
                        <i class="fa fa-save me-2"></i> Update Requisition
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function(){
    $('#requisitionForm').on('submit', function(e){
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();

        $('#formPreloader').removeClass('d-none');
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(res){
                $('#formPreloader').addClass('d-none');
                if(res.success){
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr){
                $('#formPreloader').addClass('d-none');
                Swal.fire({icon:'error', title:'Error', text:'Something went wrong!'});
            }
        });
    });
});
</script>

<style>
.preloader-overlay {
    position: absolute; top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(255,255,255,0.7);
    display: flex; align-items: center; justify-content: center;
    z-index: 10; border-radius: 10px;
}
.form-label { font-size: 1.1rem; color: #000; }
</style>
@endpush
