@extends('admin.layout.admin')

@section('content')

    <div class=" col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{_('Invoices in system')}} <a href="{{ url(route('admin.invoices.create'))}}">
                    <div class="btn btn-success btn-xs fa-plus fa"></div>
                </a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                {!! $invoices->render() !!}
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{_('Number')}}</th>
                            <th>{{_('Date')}}</th>
                            <th>{{_('Company')}}</th>
                            <th>{{_('Partner')}}</th>
                            <th>{{_('Currency')}}</th>
                            <th>{{_('Amount')}}</th>
                            <th>{{_('Action')}}</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->id}}</td>
                                <td>{{ $invoice->number}}</td>
                                <td>{{ $invoice->date}}</td>
                                <td>{{ $invoice->company->title}}</td>
                                <td>{{ $invoice->partner->name}}</td>
                                <td>{{ $invoice->currency->name}}</td>
                                <td class="text-right">{{ $invoice->amount_total}}</td>
                                {{--<td>{{ isset($invoice->closed_data_date) ? $company->closed_data_date : '-'}}</td>--}}
                                <td>
                                    {{--<a href="{{ url(route('admin.companies.users.show', $company->id))}}"><div class="btn btn-info btn-xs fa   fa-compress"></div></a>--}}

                                    {{--<a href="{{ url(route('admin.companies.edit',  $company->id))}}"><div class="btn btn-success btn-xs fa-edit fa"></div></a>--}}
                                    {{--<a href="{{ url(route('admin.companies.show',  $company->id))}}"><div class="btn btn-success btn-xs fa-info fa"></div></a>--}}
                                    {{--<a href="{{ url(route('admin.companies.destroy',  [$company->id,'method'=>'delete']))}}"><div class="btn btn-danger btn-xs fa-remove fa"></div></a>--}}

                                </td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            {!! $invoices->render() !!}
            <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
@stop