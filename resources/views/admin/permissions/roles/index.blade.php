@extends('admin.layout.admin')

@section('content')

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Permission's <b>"{{ $permission->label }}"</b> Roles list:

                {{-- 			<a href="{{ url(route('admin.users.create'))}}"><div class="btn btn-success btn-xs fa-plus fa"></div></a> --}}
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">

                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Role name</th>
                            <th>Role Label</th>
                            <th>Assigned to Permission {{ $permission->name }}</th>

                        </tr>
                        </thead>
                        {!! Form::open(['class'=>'form-horizontal', 'method'=>'put', 'route'=>['admin.permissions.roles.update', $permission->id]]) !!}
                        <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->label }}</td>
                                <td>


                                    {!! Form::checkbox('role_id[]', $role->id, $role->permissions->count() >0 ? true: false) !!}
                                    {{-- 								<a href="{{ url(route('admin.users.companies.show', $user->id))}}"><div class="btn btn-info btn-xs fa   fa-compress"></div></a>
                                                                    <a href="{{ url(route('admin.users.edit',  $user->id))}}"><div class="btn btn-success btn-xs fa-edit fa"></div></a>
                                                                    <a href="{{ url(route('admin.users.destroy',  [$user->id,'method'=>'delete']))}}"><div class="btn btn-danger btn-xs fa-remove fa"></div></a> --}}

                                </td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>



                    {!! Form::submit('Update')!!}
                    {!! Form::close() !!}
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>




@stop

@section('sidebar')


@stop