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


{!! Form::open(array('method'=>'POST','enctype'=>'multipart/form-data', 'id'=>'employee_add')) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
           <div class="col-md-4">
              <div class="form-group">
                <input type="hidden" name="id" value="{{ $employee_edit->id}}">
            <strong>Employee ID:</strong>
            {!! Form::number('employee_id', $employee_edit->employee_id, array('placeholder' => 'Employee ID','class' => 'form-control employee_id')) !!}
        </div>
    </div>
    <div class="col-md-4">
              <div class="form-group">
            <strong>Employee Name:</strong>
            {!! Form::text('employee_name', $employee_edit->employee_name, array('placeholder' => 'Employee Name','class' => 'form-control employee_name')) !!}
        </div>
    </div>
       <div class="col-md-4">
              <div class="form-group">
            <strong>Designation</strong>
            <input class="form-control" type="text" name="designation" value="{{ $employee_edit->designation}}">
        </div>
    </div>

<div class="col-md-4">
          <div class="form-group">
            <strong>Unit  Name:</strong>
           <select class="form-control unit_wise_company unit_id" name="unit_id">
             <option value="">Unit Name</option>
             @foreach($units as $unit)
             <option @if($unit->id == $employee_edit->unit_id) {{ 'selected'}} @endif value="{{ $unit->id}}">{{ $unit->unit_name}}</option>
             @endforeach
           </select>
        </div>
</div>
<div class="col-md-4">
          <div class="form-group">
            <strong>Department  Name:</strong>
           <select class="form-control department_name select2" name="department_id">
              @foreach($departments as $dep)
             <option @if($dep->id == $employee_edit->department_id) {{ 'selected'}} @endif value="{{ $dep->id}}">{{ $dep->department_name}}</option>
             @endforeach
           
           </select>
        </div>
</div>




    <div class="col-md-4">
              <div class="form-group">
            <strong>Location  Name:</strong>
           <select class="form-control location_name" name="location_id">
             @foreach($locations as $location)
             <option @if($location->id == $employee_edit->location_id) {{ 'selected'}} @endif value="{{ $location->id}}">{{ $location->location_name}}</option>
             @endforeach
           </select>
        </div>
    </div>





    <div class="col-xs-12 col-sm-12 col-md-12">
      <br>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    </div>

</div>
{!! Form::close() !!}
</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="//cdn.ckeditor.com/4.4.7/full/ckeditor.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<!-- Script -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
// In your Javascript (external.js resource or <script> tag)
$(document).ready(function() {
    $('.select2').select2();
});
</script>
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

  // daagnumbers_with_land_quantity
$(".unit_wise_company").change(function () {

            var unit_id = $(this).val();

// alert(unit_id);

            $.ajax({
                type: 'GET',
                url: "{{ route('departments.unit-wise-company')}}",
                data: { unit_id: unit_id},
// alert(JSON.stringify(data));
                dataType: 'json',
                success: function (data) {     
                // alert(data.RsDaagNumber);              

                 $(".company_name").empty();

        $('.company_name').prepend("<option value=''>" +'Please Select'+"</option>");

                    $.each(data['company_list'], function (key, company_list) {

            $('.company_name').append("<option value='" + company_list.id + "'>" + company_list.company_name +"</option>");

                    });

           
                 

// $('.landcategory').val("<option value='"+data.cat_id+"'>" + data.category +"</option>");
           
                },
                error: function (_response) {
                    alert("error");
                }

            });

});

  // unit wise department
$(".unit_wise_company").change(function () {

            var unit_id = $(this).val();

// alert(unit_id);

            $.ajax({
                type: 'GET',
                url: "{{ route('unit-wise-department')}}",
                data: { unit_id: unit_id},
// alert(JSON.stringify(data));
                dataType: 'json',
                success: function (data) {     
                // alert(data.RsDaagNumber);              

                 $(".department_name").empty();

        $('.department_name').prepend("<option value=''>" +'Please Select'+"</option>");

                    $.each(data['department_list'], function (key, department_list) {

            $('.department_name').append("<option value='" + department_list.id + "'>" + department_list.department_name +"</option>");

                    });

// $('.landcategory').val("<option value='"+data.cat_id+"'>" + data.category +"</option>");
           
                },
                error: function (_response) {
                    alert("error");
                }

            });

});

  // unit wise location
$(".unit_wise_company").change(function () {

            var unit_id = $(this).val();

// alert(unit_id);

            $.ajax({
                type: 'GET',
                url: "{{ route('unit-wise-location')}}",
                data: { unit_id: unit_id},
// alert(JSON.stringify(data));
                dataType: 'json',
                success: function (data) {     
                // alert(data.RsDaagNumber);              

                 $(".location_name").empty();

        $('.location_name').prepend("<option value=''>" +'Please Select'+"</option>");

                    $.each(data['location_list'], function (key, location_list) {

            $('.location_name').append("<option value='" + location_list.id + "'>" + location_list.location_name +"</option>");

                    });

// $('.landcategory').val("<option value='"+data.cat_id+"'>" + data.category +"</option>");
           
                },
                error: function (_response) {
                    alert("error");
                }

            });

});


   $('#employee_add').submit(function(e) {

       e.preventDefault();

       var unit_id  = $('.unit_id').val();
       var project_name  = $('.project_name').val();
       // alert(company_name);

       if (unit_id  == '') {
            Swal.fire({
              type: 'warning',
              title: 'Please Enter Unit Name',
              icon: 'warning',
              // showCloseButton: true,
              // showCancelButton: true,
              focusConfirm: false,
              allowOutsideClick: false,
              allowEscapeKey: false,

            })
       }

       else if (project_name  == '') {
            Swal.fire({
              type: 'warning',
              title: 'Please Enter Project Name',
              icon: 'warning',
              // showCloseButton: true,
              // showCancelButton: true,
              focusConfirm: false,
              allowOutsideClick: false,
              allowEscapeKey: false,

            })
       }
       let formData = new FormData(this);
       $('#image-input-error').text('');

       $.ajax({
          type:'POST',
            url:"{{ route('employees.store') }}",
           data: formData,
           contentType: false,
           processData: false,
           success: (response) => {

             Swal.fire({
            html: '<span style="color:green">Information Added</span>',
            icon: 'success',
             type: 'success',
              title: 'Employee Updated',
              // showCloseButton: true,
              // showCancelButton: true,
              focusConfirm: false,
              allowOutsideClick: false,
                allowEscapeKey: false,
             
            }).then((data) => {
                   if(data){
                     // Do Stuff here for success
                     location.reload();
                   }else{
                    // something other stuff
                   }

                })


         
               $('.saved').html('Saved');
               
           },
           error: function(response){
              console.log(response);
                $('#image-input-error').text(response.responseJSON.errors.file);
           }
       });
  });

</script>
@endsection