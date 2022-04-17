@extends('admin.layout.admin')

@section('content')

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Roles list in system <a href="{{ url(route('admin.roles.create'))}}">
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
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->id}}</td>
                                <td>{{ $role->name}}</td>
                                <td>{{ $role->label}}</td>
                                <td>
                                    <a href="{{ url(route('admin.roles.permissions.show', $role->id))}}">
                                        <div class="btn btn-info btn-xs fa   fa-compress"></div>
                                    </a>

                                    <a href="{{ url(route('admin.roles.edit',  $role->id))}}">
                                        <div class="btn btn-success btn-xs fa-edit fa"></div>
                                    </a>
                                    <a href="{{ url(route('admin.roles.destroy',  [$role->id,'method'=>'delete']))}}">
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