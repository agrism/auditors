@extends('admin.layout.admin')

@section('content')

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ __('Invoices in system') }} <a href="{{ url(route('admin.invoices.create')) }}">
                    <div class="btn btn-success btn-xs fa-plus fa"></div>
                </a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                {!! $invoices->links() !!}
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Number') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Company') }}</th>
                            <th>{{ __('Partner') }}</th>
                            <th>{{ __('Currency') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->id }}</td>
                                <td>{{ $invoice->number }}</td>
                                <td>{{ $invoice->date }}</td>
                                <td>{{ $invoice->company->title ?? null }}</td>
                                <td>{{ $invoice->partner->name ?? null }}</td>
                                <td>{{ $invoice->currency->name ?? null }}</td>
                                <td class="text-right">{{ $invoice->amount_total }}</td>
                                <td>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                {!! $invoices->links() !!}
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
@stop