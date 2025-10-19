@extends('admin.dashboard.master')


@section('main_content')
<section role="main" class="content-body">
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Add Location</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('locations.index') }}"> Back</a>
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


{!! Form::open(array('method'=>'POST','enctype'=>'multipart/form-data', 'id'=>'location_add')) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
       
        <div class="form-group">
            <strong>Unit  Name:</strong>
           <select class="form-control unit_wise_department unit_id" name="unit_id">
             <option value="">Unit Name</option>
             @foreach($units as $unit)
             <option value="{{ $unit->id}}">{{ $unit->unit_name}}</option>
             @endforeach
           </select>
        </div>
{{--         <div class="form-group">
            <strong>Department  Name:</strong>
           <select class="form-control department_lists" name="department_id">
             @foreach($department_lists as $list)
             <option value="{{ $list->id}}">{{ $list->department_name}}</option>
             @endforeach
           </select>
        </div> --}}

        <div class="form-group">
            <strong>Location Name:</strong>
            {!! Form::text('location_name', null, array('placeholder' => 'Location Name','class' => 'form-control location_name ')) !!}
        </div>

        <div class="form-group">
            <strong>Location Address:</strong>
            {!! Form::textarea('address', null, array('placeholder' => 'Location Description','class' => 'form-control address')) !!}
        </div>

    </div>
{{--     <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Permission:</strong>
            <br/>
            @foreach($permission as $value)
                <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                {{ $value->name }}</label>
            <br/>
            @endforeach
        </div>
    </div> --}}
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
      <br>
        <button type="submit" class="btn btn-primary">Submit</button>
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
$(".unit_wise_department").change(function () {

            var unit_id = $(this).val();

alert(unit_id);

            $.ajax({
                type: 'GET',
                url: "{{ route('departments.unit-wise-company')}}",
                data: { unit_id: unit_id},
// alert(JSON.stringify(data));
                dataType: 'json',
                success: function (data) {     
                // alert(data.RsDaagNumber);              

                 $(".department_lists").empty();

        $('.department_lists').prepend("<option value=''>" +'Please Select'+"</option>");

                    $.each(data['department_lists'], function (key, department_lists) {

            $('.department_lists').append("<option value='" + department_lists.id + "'>" + department_lists.department_name +"</option>");

                    });

           
                 

// $('.landcategory').val("<option value='"+data.cat_id+"'>" + data.category +"</option>");
           
                },
                error: function (_response) {
                    alert("error");
                }

            });

});


   $('#location_add').submit(function(e) {

       e.preventDefault();

       var unit_id  = $('.unit_id').val();
       var location_name  = $('.location_name').val();
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

       else if (location_name  == '') {
            Swal.fire({
              type: 'warning',
              title: 'Please Enter Location Name',
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
            url:"{{ route('locations.store') }}",
           data: formData,
           contentType: false,
           processData: false,
           success: (response) => {

             Swal.fire({
            html: '<span style="color:green">Information Added</span>',
            icon: 'success',
             type: 'success',
              title: 'Location Added',
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