@extends('admin.dashboard.master')


@section('main_content')
<section role="main" class="content-body">
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Change Setting</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('home') }}"> Back</a>
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



<button class="btn btn-primary admin_menu"> Admin </button>
<button class="btn btn-success site_menu"> Site </button>

<hr>
<br>
{!! Form::open(array('method'=>'POST','enctype'=>'multipart/form-data', 'id'=>'upload-image-form')) !!}
<div class="row">

  @if(!empty($settings->site_title))
    <div class="col-xs-12 col-sm-12 col-md-12 site_settings">
        <div class="form-group">
            <strong>Site Title :</strong>
            {!! Form::text('site_title', $settings->site_title, array('placeholder' => 'Site Title','class' => 'form-control')) !!}
        </div>
    <span class="text-danger" id="image-input-error"></span>
        <div class="form-group">
            <strong>Site Description :</strong>
            {!! Form::textarea('site_description', $settings->site_description, array('placeholder' => 'Site Description','class' => 'form-control')) !!}
        </div>
        <div class="form-group">
            <strong>Site Copyright Text :</strong>
            {!! Form::text('site_copyright_text', $settings->site_copyright_text, array('placeholder' => 'Site Copyright Text','class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            <strong>Select Site Logo:</strong> <br>
           <label> <input class="menu_location_backend" type="file" name="site_logo"> 

            <img src="{{ asset('admin_resource/assets/images/'.$settings->site_logo)}}" width="100" height="100">
         
        </div>


    </div>
    @else
       <div class="col-xs-12 col-sm-12 col-md-12 site_settings">
        <div class="form-group">
            <strong>Site Title :</strong>
            {!! Form::text('site_title',null, array('placeholder' => 'Site Title','class' => 'form-control')) !!}
        </div>
    <span class="text-danger" id="image-input-error"></span>
        <div class="form-group">
            <strong>Site Description :</strong>
            {!! Form::text('site_description', null, array('placeholder' => 'Site Description','class' => 'form-control')) !!}
        </div>
        <div class="form-group">
            <strong>Site Copyright Text :</strong>
            {!! Form::textarea('site_copyright_text', null, array('placeholder' => 'Site Copyright Text','class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            <strong>Select Site Logo:</strong> <br>
           <label> <input class="menu_location_backend" type="file" name="site_logo"> 

               <img src="" width="100" height="100">
         
        </div>


    </div>
    @endif


 @if(!empty($settings->admin_title))

    <div class="col-xs-12 col-sm-12 col-md-12 admin_settings">
        <div class="form-group">
            <strong>Admin Title :</strong>
            {!! Form::text('admin_title', $settings->admin_title, array('placeholder' => 'Admin Title','class' => 'form-control')) !!}
        </div>
        <div class="form-group">
            <strong>Admin Description :</strong>
            {!! Form::text('admin_description', $settings->admin_description, array('placeholder' => 'Admin Description','class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            <strong>Admin Logo:</strong> <br>
           <label> <input class="menu_location_backend" type="file" name="admin_logo"> 
            @if(!empty($settings->admin_logo)) 
               <img src="{{ asset('admin_resource/assets/images/'.$settings->admin_logo)}}" width="100" height="100">
               @endif
         
        </div>

    </div>

    @else

    <div class="col-xs-12 col-sm-12 col-md-12 admin_settings">
        <div class="form-group">
            <strong>Admin Title :</strong>
            {!! Form::text('admin_title', null, array('placeholder' => 'Admin Title','class' => 'form-control')) !!}
        </div>
        <div class="form-group">
            <strong>Admin Description :</strong>
            {!! Form::text('admin_description', null, array('placeholder' => 'Admin Description','class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            <strong>Admin Logo:</strong> <br>
           <label> <input class="menu_location_backend" type="file" name="admin_logo"> 
            @if(!empty($settings->admin_logo)) 
               <img src="{{ asset('admin_resource/assets/images/'.$settings->admin_logo)}}" width="100" height="100">
                @endif
         
        </div>

    </div>

  @endif


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
        <button type="submit" class="btn btn-primary saved">Submit</button>
    </div>
</div>
{!! Form::close() !!}
</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">


    $(".admin_settings").hide();
$(".admin_menu").click(function() {
   
        $(".site_settings").hide(300);
        $(".admin_settings").show(300);
  
});$(".site_menu").click(function() {
   
        $(".admin_settings").hide(300);
        $(".site_settings").show(300);
  
});




</script>

<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


   $('#upload-image-form').submit(function(e) {

       e.preventDefault();
       let formData = new FormData(this);
       $('#image-input-error').text('');

       $.ajax({
          type:'POST',
            url:"{{ route('settings.store') }}",
           data: formData,
           contentType: false,
           processData: false,
           success: (response) => {

             Swal.fire({
            html: '<span style="color:green">Information Updated</span>',
            icon: 'success',
             type: 'success',
              title: 'Setting Edit',
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