@extends('admin.layout.admin')

@section('content')

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Users list in system <a href="{{ url(route('admin.users.create'))}}">
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
                            <th>email</th>
                            <th>action</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <a href="{{ url(route('admin.users.companies.show', $user->id))}}">
                                        <div class="btn btn-info btn-xs fa   fa-compress"></div>
                                    </a>
                                    <a href="{{ url(route('admin.users.edit',  $user->id))}}">
                                        <div class="btn btn-success btn-xs fa-edit fa"></div>
                                    </a>
                                    <a href="{{ url(route('admin.users.destroy',  [$user->id,'method'=>'delete']))}}">
                                        <div class="btn btn-danger btn-xs fa-remove fa"></div>
                                    </a>
                                    <a href="{{ url(route('admin.prepare-login-as-user',  [$user->id]))}}">
                                        <div class="btn btn-warning btn-xs fa-sign-in fa"></div>
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

@section('sidebar')


@stop