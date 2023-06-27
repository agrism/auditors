@extends('admin.layout.admin')

@section('content')

    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                User info <a href="{{ url(route('admin.users.create'))}}">
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
                            <th>companies</th>


                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>company</th>
                                        <th>action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($user->companies as $company)
                                        <tr>
                                            <td>
                                                {{$company->title}}
                                            </td>
                                            <td>
                                                <a href="{{ url(route('admin.home',  [$user->id,'method'=>'delete']))}}">
                                                    <div class="btn btn-danger btn-xs fa-remove fa"></div>
                                                </a>

                                            </td>

                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>
                            </td>

                        </tr>


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