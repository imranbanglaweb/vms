@extends('Admin.master')
@section('main_content')
<div class="inner-wrapper">
<section role="main" class="content-body">
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Permission</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('permissions.index') }}"> <i class="fa fa-arrow-left"></i> Back</a>
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


{!! Form::model($permission_edit, ['method' => 'PATCH','route' => ['permissions.update', $name]]) !!}
<div class="row">

    <div class="col-xs-12 col-sm-12 col-md-12">
         <div class="col-xs-12 col-sm-12 col-md-12">
    
        <div class="form-group">
            <strong>Table Name:</strong>
            @if(!empty($permission_edit->table_name))
            {!! Form::text('table_name', $permission_edit->table_name, array('placeholder' => 'Table Name','class' => 'form-control')) !!}
            @else
            {!! Form::text('table_name', null, array('placeholder' => 'Table Name','class' => 'form-control')) !!}

            @endif
         
        </div>
        <div class="form-group">
            <strong>Is User Defined *:</strong>
            {!! Form::text('is_user_defined', null, array('placeholder' => 'User Defined','class' => 'form-control')) !!}
        </div>
    </div>
        <div class="form-group">
            <strong>Permission:</strong>
            <br>
            <br/>
            @foreach($permission as $value)
                <label>
                   {{--  {{ Form::checkbox('permission[]', $value->name, in_array($value->name, $id) ? true : false, array('class' => 'name')) }} --}}

                     <input type="checkbox" id="permission-{{$value->id}}" name="permission" class="permission" data-permission-name="{{$value->name}}" value="{{$value->name}}" @if($value->name == $name ){{ 'checked'}}@else {{ false}}@endif>

                {{ $value->name }}</label>
            <br/>
            @endforeach
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>
{!! Form::close() !!}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
        $('document').ready(function () {
            // $('.toggleswitch').bootstrapToggle();

            // $('.permission-group').on('click', function(){
            //     // alert();
            //     $('.the-permission').find("input[type='checkbox']").prop('checked', true);
            // });

            $('.permission').on('click', function(){

                 let check_permission_name = $(this).val();
                 let permission_name = $(this).data('permission-name');

                 if (permission_name = check_permission_name) {

                 // alert(check_permission_name);

                $('.permissions').find("input[type='checkbox']").prop('checked', true);
                return false;
                 }
                 else{

                $('.permissions').find("input[type='checkbox']").prop('checked', false);
                return false;
                 }
            });


        });
    </script>

@endsection
