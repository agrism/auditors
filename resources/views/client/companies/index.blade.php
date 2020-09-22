@extends('client.layout.master')

@section('content')

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Select Company to manage
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Reg.No</th>
                            {{-- <th>action</th> --}}

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->companies as $company)



                            <tr>

                                <td>
                                    <a href="{{ url(route('client.companies.show', $company->id)) }}">{{ $company->id}}</a>
                                </td>
                                <td>
                                    <a href="{{ url(route('client.companies.show', $company->id)) }}">{{ $company->title}}</a>
                                </td>
                                <td>
                                    <a href="{{ url(route('client.companies.show', $company->id)) }}">{{ $company->registration_number}}</a>
                                </td>

                                {{--							<td>--}}
                                {{--							--}}
                                {{--								<a href="{{ url(route('client.companies.show', $company->id) ) }}"><div class="btn btn-info btn-xs fa fa-info"></div>--}}
                                {{--								</a>--}}

                                {{--								<a href="{{ url(route('client.companies.edit',  $company->id) ) }}"><div class="btn btn-success btn-xs fa-edit fa"></div>--}}
                                {{--								</a>--}}

                                {{--								--}}
                                {{--								<a href="{{ url(route('client.companies.destroy',  [$company->id,'method'=>'delete']))}}"><div class="btn btn-danger btn-xs fa-remove fa"></div>--}}
                                {{--								</a>--}}
                                {{--								--}}

                                {{--							</td>--}}


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