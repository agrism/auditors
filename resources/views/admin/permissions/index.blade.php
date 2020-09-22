@extends('admin.layout.admin')

@section('content')

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Permissions list in system <a href="{{ url(route('admin.permissions.create'))}}">
                    <div class="btn btn-success btn-xs fa-plus fa"></div>
                </a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Label</th>
                            <th>action</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($permissions as $permission)
                            <tr>
                                <td>{{ $permission->id}}</td>
                                <td>{{ $permission->name}}</td>
                                <td>{{ $permission->label}}</td>
                                <td>
                                    <a href="{{ url(route('admin.permissions.roles.show', $permission->id))}}">
                                        <div class="btn btn-info btn-xs fa   fa-compress"></div>
                                    </a>

                                    <a href="{{ url(route('admin.permissions.edit',  $permission->id))}}">
                                        <div class="btn btn-success btn-xs fa-edit fa"></div>
                                    </a>
                                    <a href="{{ url(route('admin.permissions.destroy',  [$permission->id,'method'=>'delete']))}}">
                                        <div class="btn btn-danger btn-xs fa-remove fa"></div>
                                    </a>

                                </td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
@stop