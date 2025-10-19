@extends('admin.dashboard.master')


@section('main_content')
<section role="main" class="content-body">
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Permission Management</h2>
        </div>
        <div class="pull-right">
        @can('permission-create')
            <a class="btn btn-success" href="{{ route('permissions.create') }}"> <i class="fa fa-plus"></i>    Add Permission</a>
            @endcan
        </div>
    </div>
</div>


@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

@if ($message = Session::get('danger'))
    <div class="alert alert-danger">
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
    <table class="table table-bordered table-striped mb-none" id="myTable">
<thead>
      <tr>
     <th>No</th>
     <th>Name</th>
     <th width="280px">Action</th>
  </tr>
</thead>
  <tbody>
    @foreach ($permissions as $key => $permission)
    <tr>
        <td>{{ $key+1 }}</td>
        <td>{{ $permission->name }}</td>
        <td>
          {{--   <a class="btn btn-info" href="{{ route('permissions.show',$permission->id) }}"> <i class="fa fa-eye"></i> Show</a> --}}
            @can('permission-edit')
                <a class="btn btn-primary" href="{{ route('permissions.edit',$permission->name) }}"><i class="fa fa-edit"></i> Edit</a>
            @endcan
            @can('permission-delete')

                {!! Form::open(['method' => 'DELETE','route' => ['permissions.destroy', $permission->id],'style'=>'display:inline']) !!}
                    <i class="fa fa-trash-o"></i> 
                     {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
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


@endsection