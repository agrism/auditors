@extends('admin.layout.admin')

@section('content')

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Company Structaral Units <a
                        href="{{ url(route('admin.company.structuralunits.create', $company['id'])) }}">
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
                            <th>action</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($structuralunits as $unit)
                            <tr>
                                <td>{{ $unit->id}}</td>
                                <td>{{ $unit->title}}</td>
                                <td>

                                    <a href="{{ url(route('admin.company.structuralunits.edit',  [$company['id'], $unit->id]))}}">
                                        <div class="btn btn-success btn-xs fa-edit fa"></div>
                                    </a>

                                    <a href="{{ url(route('admin.company.structuralunits.destroy', [$company['id'],$unit->id ,'method'=>'delete']))}}">
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