@extends('admin.dashboard.master')


@section('main_content')
<section role="main" class="content-body">
  <div class="row">
      <div class="col-lg-12 margin-tb">
          <div class="pull-left">
              <h2>Edit Employee</h2>
          </div>
          <div class="pull-right">
              <a class="btn btn-primary" href="{{ route('employees.index') }}"> Back</a>
          </div>
      </div>
  </div>

  @if (count($errors) > 0)
      <div class="alert alert-danger">
          <strong>Whoops!</strong> There were some problems with your input.<br><br>
          <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
          </ul>
      </div>
  @endif

  {!! Form::open(array('method'=>'POST','enctype'=>'multipart/form-data', 'id'=>'employee_edit')) !!}
  <div class="row">
      <div class="col-md-8">
          <div class="form-row">
              <div class="form-group col-md-6">
                  <label><strong><i class="fa fa-sitemap text-primary mr-1"></i> Unit</strong></label>
                  <select class="form-control unit_wise_company unit_id select2" name="unit_id">
                      <option value="">Please select</option>
                      @foreach($units as $unit)
                          <option value="{{ $unit->id}}" @if($unit->id == $employee_edit->unit_id) selected @endif>{{ $unit->unit_name}}</option>
                      @endforeach
                  </select>
              </div>
              <div class="form-group col-md-6">
                  <label><strong><i class="fa fa-layer-group text-primary mr-1"></i> Department</strong></label>
                  <select class="form-control department_name" name="department_id">
                      <option value="">Please select</option>
                      @foreach($departments as $dep)
                          <option value="{{ $dep->id }}" @if($dep->id == $employee_edit->department_id) selected @endif>{{ $dep->department_name }}</option>
                      @endforeach
                  </select>
              </div>
          </div>
          <!-- other fields omitted for brevity; keep existing form fields as in create view -->
      </div>

      <div class="col-md-4">
          <div class="card">
              <div class="card-body text-center">
                  <div class="form-group">
                      <label><strong>Photo</strong></label>
                      <div class="mb-2">
                          <img id="photo-preview" src="{{ asset('public/uploads/default-avatar.png') }}" alt="preview" style="max-width:100%; height:150px; object-fit:cover;" />
                      </div>
                      {!! Form::file('photo', ['class'=>'form-control-file','id'=>'photo-input']) !!}
                  </div>
                  <div class="mt-3">
                      <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-save"></i> Update Employee</button>
                      <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-block mt-2"><i class="fa fa-times"></i> Cancel</a>
                  </div>
              </div>
          </div>
      </div>
  </div>
  {!! Form::close() !!}

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

  <script>
  $(document).ready(function(){
      $('.select2').select2();

      // delegated handler for unit change — populates department selects
      $(document).on('change', '.unit_wise_company', function () {
          var unit_id = $(this).val();

          $.ajax({
              type: 'GET',
              url: "{{ route('unit-wise-department') }}",
              data: { unit_id: unit_id },
              dataType: 'json',
              success: function (data) {
                  console.log('unit-wise-department (employee.edit) response:', data);
                  if ($('.department_name').data('select2')) {
                      try { $('.department_name').select2('destroy'); } catch(e) { console.warn('select2 destroy failed', e); }
                  }
                  var previous = $('.department_name').val();
                  $(".department_name").empty().append("<option value=''>Please Select</option>");
                  $.each(data['department_list'] || [], function (k, d) {
                      $('.department_name').append("<option value='" + d.id + "'>" + d.department_name + "</option>");
                  });
                  if (previous) { $('.department_name').val(previous); }
                  $('.department_name').trigger('change');
              },
              error: function (xhr, status, err) {
                  console.error('Error loading departments for unit', unit_id, status, err);
              }
          });
      });

      // photo preview
      $(document).on('change', '#photo-input', function(e){
          const [file] = this.files;
          if (file) {
              const url = URL.createObjectURL(file);
              $('#photo-preview').attr('src', url);
          }
      });
  });
  </script>

@endsection