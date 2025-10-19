@extends('admin.dashboard.master')


@section('main_content')
<style>
  #menu_list {
  padding: 0px;
}
#menu_list td {
  list-style: none;
  margin-bottom: 10px;
  border: 1px solid #d4d4d4;
      -webkit-border-radius: 3px;
      -moz-border-radius: 3px;
      border-radius: 3px;
      border-color: #D4D4D4 #D4D4D4 #BCBCBC;
      padding: 6px;
      cursor: move;
      background: #f6f6f6;
      background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #ededed 100%);
      background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(47%,#f6f6f6), color-stop(100%,#ededed));
      background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
      background: -o-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
      background: -ms-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
      background: linear-gradient(to bottom,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ededed',GradientType=0 );
}
</style>
<section role="main" class="content-body">
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Unit Manage</h2>
        </div>
        <div class="pull-right">
        @can('unit-create')
            <a class="btn btn-success" href="{{ route('units.create') }}"> <i class="fa fa-plus"></i>    Add Unit</a>
            @endcan
        </div>
    </div>
</div>


@if ($message = Session::get('success'))
    <div class="alert alert-success myElem">
        <p>{{ $message }}</p>
    </div>
@endif
@if ($message = Session::get('danger'))
    <div class="alert alert-danger myElem">
        <p>{{ $message }}</p>
    </div>
@endif


<section class="panel">
                            <header class="panel-heading">
                                <div class="panel-actions">
                                    <a href="#" class="fa fa-caret-down"></a>
                                    <a href="#" class="fa fa-times"></a>
                                </div>
                        
                               
                            </header>
                            <div class="panel-body">
    <table class="table table-bordered table-striped mb-none" id="datatable-default">
<thead>
      <tr>
     <th>No</th>
     <th>Unit Name</th>
     <th>Unit Code</th>
     <th>Unit Description</th>
     <th width="15%">Action</th>
  </tr>
</thead>
  <tbody id="menu_list" class="" >
    @foreach ($units as $key => $unit)
    <tr  id="{{ $unit->id }}">
        <td>{{ ++$key }}</td>
        <td >{{ $unit->unit_name }}</td>
        <td >{{ $unit->unit_code }}</td>
        <td >{{ $unit->remarks }}</td>
        <td>
             @can('unit-edit')
                <a class="btn btn-primary" href="{{ route('units.edit',$unit->id) }}"><i class="fa fa-edit"></i> </a>
            @endcan
             @can('unit-delete')
     <button class="btn btn-danger deleteUser" data-uid="{{$unit->id}}"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
        
              @endcan
        </td>
    </tr>
    @endforeach
  </tbody>
</table>
</section>
</div>
</div>
</div>
</section>
<div id="applicantDeleteModal" class="modal modal-danger fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">


                {!! Form::open(['method' => 'DELETE','route' => ['units.destroy', $unit->id],'style'=>'display:inline']) !!}
                    {{-- <i class="fa fa-trash-o"></i>  --}}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title text-center" id="custom-width-modalLabel">
                    Delete Unit</h4>
                </div>
                <div class="modal-body">
                    <h4 class="text-center text-danger">Are You Sure Delete Unit</h4>
                    <input type="hidden" name="u_id" id="u_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger waves-effect remove-data-from-delete-form">Delete</button>
                </div>

             {!! Form::close() !!}
        </div>
    </div>
</div>
</section>
{{-- <script  src="{{ asset('js/')}}/function.js"></script> --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>

$(document).on('click','.deleteUser',function(){
    var u_id=$(this).attr('data-uid');
    $('#u_id').val(u_id); 
    $('#applicantDeleteModal').modal('show'); 
});


    $(".myElem").show().delay(5000).fadeOut();
    $(function(){
      $("#menu_list").sortable({
        stop: function(){
          $.map($(this).find('tr'), function(el) {
            var itemID = el.id;
            var itemIndex = $(el).index();
            // alert(itemIndex);
            $.ajax({
              url:'{{URL::to("order-menu")}}',
              type:'GET',
              dataType:'json',
              data: {itemID:itemID, itemIndex: itemIndex},
            })
          });
        }
      });
    });
  </script>
@endsection