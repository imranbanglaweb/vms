<div class="card shadow-sm border-0 rounded-3 mx-auto" style="max-width: 1000px;">
    <div class="card-body bg-light p-4">
        <form id="vehicleTypeForm" action="{{ $action }}" method="POST">
            @csrf
            @if($method == 'PUT')
                @method('PUT')
            @endif

            <div class="mb-3">
                <label class="form-label fw-semibold">Vehicle Type Name *</label>
                <div class="input-group input-group-lg shadow-sm">
                    <span class="input-group-text bg-white"><i class="fa fa-car text-secondary"></i></span>
                    <input type="text" name="name" class="form-control fs-6" 
                        placeholder="Enter vehicle type name"
                        value="{{ old('name', $vehicleType->name ?? '') }}">
                </div>
                <small class="text-danger error-text name_error"></small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <div class="input-group input-group-lg shadow-sm">
                    <span class="input-group-text bg-white"><i class="fa fa-align-left text-secondary"></i></span>
                    <textarea name="description" class="form-control fs-6" rows="3" placeholder="Description">{{ old('description', $vehicleType->description ?? '') }}</textarea>
                </div>
                <small class="text-danger error-text description_error"></small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Status *</label>
                <div class="input-group input-group-lg shadow-sm">
                    <span class="input-group-text bg-white"><i class="fa fa-toggle-on text-secondary"></i></span>
                    <select name="status" class="form-select fs-6">
                        <option value="1" {{ old('status', $vehicleType->status ?? '') == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $vehicleType->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <small class="text-danger error-text status_error"></small>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-4 py-2 fs-6" id="submitBtn">
                    <span class="spinner-border spinner-border-sm d-none" id="loader" role="status"></span>
                    <i class="fa fa-save"></i> {{ $method == 'PUT' ? 'Update' : 'Save' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function() {
    $('#vehicleTypeForm').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');
        let method = form.find('input[name="_method"]').val() || 'POST';
        let formData = form.serialize();

        $('.error-text').text('');
        $('#loader').removeClass('d-none');
        $('#submitBtn').attr('disabled', true);

        $.ajax({
            url: url,
            method: method,
            data: formData,
            success: function (response) {
                $('#loader').addClass('d-none');
                $('#submitBtn').attr('disabled', false);

                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    if (method === 'POST') form[0].reset();
                }
            },
            error: function (xhr) {
                $('#loader').addClass('d-none');
                $('#submitBtn').attr('disabled', false);

                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        $('.' + key + '_error').text(value[0]);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong! Please try again later.'
                    });
                }
            }
        });
    });
});
</script>

<style>
.form-label { font-size: 2rem; color: #000; }
.input-group-text { width: 45px; justify-content: center; }
.form-control, .form-select { font-size: 2rem !important; }
.btn { font-size: 2rem; }
.card { background-color: #fff; padding: 20px; }
</style>
@endpush
