@extends('client.layout.master')

@section('content')

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Optional receivers of incoming payments <a href="{{ url(route('client.companies.bank.create'))}}">
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
                            <th>Comment</th>
                            <th>Name</th>
                            <th>Bank</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($banks as $bank)
                            <tr class="line" style="cursor: pointer">
                                <td>{{ $bank->id}}</td>
                                <td>{{ $bank->comment}}</td>
                                <td>{{ $bank->payment_receiver}}</td>
                                <td>{{ $bank->bank}}</td>

                            </tr>
                            <tr class="hidden actions" style="background-color: #e3e3e3">
                                <td class="text-center" colspan="100">
                                    {{-- <a href="{{ url(route('client.companies.bank.show', $bank->id))}}"><div class="btn btn-info btn-xs fa   fa-info"></div></a> --}}

                                    <a href="{{ url(route('client.companies.bank.edit',  $bank->id))}}">
                                        <div class=" fa-edit fa fa-2x text-success"></div>
                                    </a>

                                    <a href="{{ url(route('client.companies.bank.destroy',  [$bank->id,'method'=>'delete']))}}">
                                        <div class="fa-remove fa fa-2x text-danger"></div>
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

@section('js')
    <script>
        $('.line').on('click', function(){
            let that = this;
            let shouldStayHidden = !$(that).next().hasClass('hidden')

            $('.actions').addClass('hidden');
            if(shouldStayHidden){
                return;
            }
            $(that).next().removeClass('hidden');
        });
    </script>
@stop