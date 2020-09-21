@extends('admin.layout.admin')

@section('content')

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Role's <b>"{{ $role->label }}"</b> Permission list:

                {{-- 			<a href="{{ url(route('admin.users.create'))}}"><div class="btn btn-success btn-xs fa-plus fa"></div></a> --}}
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">

                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Permission name</th>
                            <th>Permission Label</th>
                            <th>Assigned to Role {{ $role->label }} [{{ $role->name }}]</th>

                        </tr>
                        </thead>
                        {!! Form::open(['class'=>'form-horizontal', 'method'=>'put', 'route'=>['admin.roles.permissions.update', $role->id]]) !!}
                        <tbody>
                        @foreach($permissions as $permission)
                            <tr>
                                <td>{{ $permission->id }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->label }}</td>
                                <td>


                                    {!! Form::checkbox('permission_id[]', $permission->id, $permission->roles->count() >0 ? true: false) !!}
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