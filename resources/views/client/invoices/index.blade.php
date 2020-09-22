@extends('client.layout.master')

@section('content')

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Invoice list in system <a href="{{ url(route('client.invoices.create'))}}">
                        <div class="btn btn-success btn-xs fa-plus fa"></div>
                    </a></h3>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">


                        <thead>
                        <tr>
                            <th>{!! sort_employees_by('id', '#', $params) !!}</th>
                            <th>{!! sort_employees_by('number', 'Number', $params) !!}</th>
                            <th>{!! sort_employees_by('date', 'Date', $params) !!}</th>
                            {{--<th>{!! sort_employees_by('payment_date', 'Payment Date', $params) !!}</th>--}}
                            <th>{!! sort_employees_by('invoicetypename', 'type', $params) !!}</th>
                            <th>{!! sort_employees_by('structuralunitname', 'Structural unit', $params) !!}</th>
                            <th>{!! sort_employees_by('partnername', 'Partner', $params) !!}</th>
                            <th>{!! sort_employees_by('details_self', 'Details', $params) !!}</th>
                            <th>{!! sort_employees_by('currency_name', 'Currency', $params) !!}</th>
                            <th>{!! sort_employees_by('amount_total', 'Amount', $params) !!}</th>
                            <th>action</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            {{--filter form--}}
                            {!! Form::open(['method'=>'get', 'route'=>'client.invoices.index']) !!}
                            <td></td>
                            <td></td>
                            <td></td>
                            {{--<td></td>--}}
                            <td>{!! Form::select('filter[invoicetype_id]', $invoicetypes->pluck('title', 'id'), isset($params) && isset($params['filter']['invoicetype_id']) && $params['filter']['invoicetype_id'] !='' ? $params['filter']['invoicetype_id'] : null, ['class'=>'form-control input-sm', 'placeholder'=>'filter by type']) !!}</td>
                            <td>{!! Form::select('filter[structuralunit_id]', $structuralunits->pluck('title', 'id'), isset($params) && isset($params['filter']['structuralunit_id']) && $params['filter']['structuralunit_id'] !='' ? $params['filter']['structuralunit_id'] : null, ['class'=>'form-control input-sm', 'placeholder'=>'filter by struct.unit']) !!}</td>
                            <td>{!! Form::select('filter[partner_id]', $partners->pluck('name', 'id'), isset($params) && isset($params['filter']['partner_id']) && $params['filter']['partner_id'] !='' ? $params['filter']['partner_id'] : null, ['class'=>'form-control input-sm', 'placeholder'=>'filter by partner']) !!}</td>
                            <td>{!! Form::text('filter[details_self]', isset($params) && isset($params['filter']['details_self']) && $params['filter']['details_self'] !=''  ?  $params['filter']['details_self'] : null, ['class'=>'form-control input-sm', 'placeholder'=>'text']) !!}</td>
                            <td></td>
                            <td></td>
                            <td class="text-valign-center">
                                {{-- <a href="{{ url(route('client.invoices.index'))}}"><div class="btn btn-info  fa   fa-filter"></div></a>  --}}

                                <button class="btn btn-default btn-xs fa fa-filter "></button>


                            </td>
                            {!! Form::close() !!}

                        </tr>
                        @foreach($invoices as $invoice)
                            <tr class="{{ (preg_match('/copy/',$invoice->number)) ? 'danger' : null }}">
                                <td>{{ $invoice->id}}</td>
                                <td id="td{{$invoice->id}}">{{ $invoice->number}}</td>
                                <td>

                                    {{ $invoice->date}}

                                    @if(isset($invoice) && $invoice['is_closed_for_edit'])
                                        {{--                                    @if(isset($invoice) )--}}
                                        <i class="fa fa-lock"></i>
                                    @endif

                                </td>
                                {{--<td>{{ $invoice->payment_date}}</td>--}}
                                <td>{{ $invoice->invoicetypename}}</td>
                                <td>{{ $invoice->structuralunitname}}</td>
								<?php
								$partnername = str_replace(
									'Sabiedrība ar ierobežotu atbildību', 'SIA', $invoice->partnername
								);
								$partnername = str_replace('Akciju sabiedrība', 'A/S', $partnername);
								?>

                                <td>{{ $partnername }}</td>
                                <td>{{ $invoice->details_self}}</td>
                                <td>{{ $invoice->currency_name}}</td>
                                <td class="text-right">{{ number_format($invoice->amount_total, 2)}}</td>

                                <td>
                                    <div class="showActionOptions  fa  fa-plus-square-o fa-lg " style="cursor:pointer"

                                    ></div>
                                    <div class="actionOptionns hide" style="z-index: 2; position:absolute;">

                                        <button class="btn btn-warning btn-xs fa fa-file showButton1"
                                                data-toggle1="tooltip" title="{{ _("View") }}" data-placement="top"
                                                data-toggle="modal"
                                                data-target="#myModalShow"
                                                action-url="{{ url(route('client.invoices.show', $invoice->id))}}"></button>

                                        <button class="btn btn-info btn-xs fa fa-copy copyButton1"
                                                data-toggle1="tooltip" title="{{ _("Copy") }}" data-placement="top"
                                                data-toggle="modal"
                                                data-target="#myModalCopy"
                                                action-url="{{ url(route('client.invoices.copy', $invoice->id))}}"></button>

                                        @if($invoice->is_locked)

                                            @if(\Auth::user()->isAdmin())
                                                <button class="btn btn-info btn-xs fa fa-lock unlockButton1"
                                                        data-toggle1="tooltip" title="{{ _("UnLock") }}"
                                                        data-placement="top"
                                                        data-toggle="modal"
                                                        data-target="#myModalUnLock"
                                                        invoice-id="{{ $invoice->id }}"
                                                        current-invoice-href="{{route('client.invoices.getCurrentInvoice', $invoice->id ) }}"
                                                        edit-invoice-number-href="{{route('client.invoices.updateInvoiceNumber', $invoice->id ) }}"
                                                        action-url="{{ url(route('client.invoices.unlock', $invoice->id))}}"></button>
                                            @else
                                                <div class="btn btn-info btn-xs fa   fa-lock"></div>
                                            @endif
                                        @else

                                            <button class="btn btn-info btn-xs fa fa-unlock lockButton1"
                                                    data-toggle1="tooltip" title="{{ _("Lock") }}" data-placement="top"
                                                    data-toggle="modal"
                                                    data-target="#myModalLock"
                                                    invoice-id="{{ $invoice->id }}"
                                                    current-invoice-href="{{route('client.invoices.getCurrentInvoice', $invoice->id ) }}"
                                                    edit-invoice-number-href="{{route('client.invoices.updateInvoiceNumber', $invoice->id ) }}"
                                                    action-url="{{ url(route('client.invoices.lock', $invoice->id))}}"></button>

                                            {{-- 	<a href="{{ url(route('client.invoices.lock', $invoice->id))}}"><div class="btn btn-info btn-xs fa   fa-unlock"></div></a> --}}

                                            <a href="{{ url(route('client.invoices.edit',  $invoice->id))}}">
                                                <div class="btn btn-success btn-xs fa-edit fa"
                                                     data-toggle1="tooltip" title="{{_("Edit")}}" data-placement="top"
                                                ></div>
                                            </a>

                                            <button type="button"
                                                    class="btn btn-danger btn-xs fa-remove fa deleteButton1"
                                                    data-toggle1="tooltip" title="{{_("Delete")}}" data-placement="top"
                                                    data-toggle="modal"
                                                    data-target="#myModal"
                                                    action-url="{{ url(route('client.invoices.destroy',  [$invoice->id,'method'=>'delete']))}}"></button>

                                            {{-- <a href="{{ url(route('client.invoices.destroy',  [$invoice->id,'method'=>'delete']))}}"><div class="btn btn-danger btn-xs fa-remove fa"></div></a> --}}
                                        @endif
                                    </div>

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

@section('modals')
    {{--<!-- Modal delete invoice-->--}}
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Confirm</h4>
                </div>
                <div class="modal-body">
                    <h3>Are you sure you want to delete this invoice?</h3>
                </div>
                <div class="modal-footer">
                    <a href="#" id="linkToDeleteInvoice">
                        <button type="button" class="btn btn-danger">Delete</button>
                    </a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>

            </div>

        </div>
    </div>

    <!-- Modal2 -->
    {{--model create copy--}}
    <div id="myModalCopy" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Confirm</h4>
                </div>
                <div class="modal-body">
                    <h3>Are you sure to create copy of this invoice?</h3>
                </div>
                <div class="modal-footer">
                    <a href="#" id="linkToCopyInvoice">
                        <button type="button" class="btn btn-info">Copy</button>
                    </a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>

            </div>

        </div>
    </div>

    <!-- Modal2   LOCKING -->
    <div id="myModalLock" class="modal fade" role="dialog">
        <div class="modal-dialog"
             style=""
        >

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Confirm</h4>
                </div>
                <div class="modal-body">
                    <h3>There will not possibility to edit/changa/delete locked invoice. Are you sure to lock this
                        invoice?</h3>


                    <div class="alert alert-danger"
                         style="display:flex; flex-wrap: wrap; align-items: flex-start;"
                    >
                        <div
                                {{--style="float:left;"--}}
                        >
                            Current invoice number
                        </div>
                        <div
                                {{--style="float:left;"--}}
                        >:&nbsp;
                        </div>

                        <div style="display:flex ">
                            <div>[</div>
                            <div id="currentInvoiceNumber"
                                 {{--style="float:left; background-color: white;padding-left: 15px;padding-right: 15px;"--}}
                                 style="background-color: white;"
                            >
                            </div>
                            <div>]</div>
                            <div>&nbsp;</div>
                        </div>

                        <div>
                            <button id="show-edit-option" class="fa btn btn-primary btn-xs  fa-edit"
                                    {{--style="float:left;"--}}
                            ></button>
                        </div>
                        <div>&nbsp;</div>

                        <div id="edit-invoice-number"
                             {{--style="display:none;float:left;"--}}
                             style="display:none"
                        >

                            <div style="display:flex;align-items: center;">
                                <div>
                                    <input id="input-edit-invoice-number" type="text" value="" name="number"
                                           action=""
                                           style="line-height: 10px; width: 150px "
                                    >
                                </div>
                                <div>&nbsp;</div>
                                <div>
                                    <button id="update-invoice-button"
                                            class="btn btn-primary btn-xs fa fa-save"></button>
                                </div>
                                <div id="input-edit-invoice-number-href" action=""></div>
                                <div id="invoice-id" invoice-id=""></div>
                            </div>
                        </div>

                    </div>
                    <div>
                        <h4>List of last 5 closed invoices:</h4>
                        {{--<div class="alert alert-info">--}}
                        <table class="table table-striped table-bordered table-hover">
                            <thead></thead>
                            <th>No</th>
                            <th>Date</th>
                            <th>Partner</th>
                            <tbody id="last5invoices"></tbody>
                        </table>
                        {{--</div>--}}
                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="linkToLockInvoice">
                        <button type="button" class="btn btn-warning">Lock</button>
                    </a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>

            </div>

        </div>
    </div>

    <!-- Modal2  UN-LOCK -->
    <div id="myModalUnLock" class="modal fade" role="dialog">
        <div class="modal-dialog"
             style=""
        >
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Confirm</h4>
                </div>
                <div class="modal-body">
                    <h3>Are you sure to unlock this invoice?</h3>
                </div>
                <div class="modal-footer">
                    <a href="#" id="linkToUnLockInvoice">
                        <button type="button" class="btn btn-warning">UnLock</button>
                    </a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    {{--  Modal3  invoice (show in html of save to file) --}}
    <div id="myModalShow" class="modal fade" role="dialog">
        <div class="modal-dialog"
             style=""
        >
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Confirm</h4>
                </div>
                <div class="modal-body">
                    <h3>What to do with this invoice:</h3>
                </div>
                <div class="modal-footer">
                    <a href="#" id="linkToShowInvoice0" target="_blank">
                        <button type="button" class="btn btn-warning">View</button>
                    </a>
                    <a href="#" id="linkToShowInvoice1">
                        <button type="button" class="btn btn-warning">Save to PDF</button>
                    </a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('js')
    <script type="text/javascript">
        $(document).ready(function () {

            $('[data-toggle1="tooltip"]').tooltip();

            $('.showActionOptions').on('click', function () {
                $(this).next().toggleClass('hide');
                $(this).toggleClass('fa-minus-square-o').toggleClass('fa-plus-square-o');
            });


            $('.deleteButton1').on('click', function () {
                var url = $(this).attr('action-url');
                // console.log('clisked');
                $('#linkToDeleteInvoice').attr('href', url);
            });

            $('.copyButton1').on('click', function () {
                var url = $(this).attr('action-url');
                // console.log('clicked');
                $('#linkToCopyInvoice').attr('href', url);
            });

            $('.showButton1').on('click', function () {
                var url = $(this).attr('action-url');
                // console.log('clicked');
                $('#linkToShowInvoice0').attr('href', url + '?type=html');
                $('#linkToShowInvoice1').attr('href', url);
//                $('#myModalShow').modal('hide');
            });


            $('#myModalShow').on('click', function () {
                $('#myModalShow').modal('hide');
            });

            $('.unlockButton1').on('click', function () {
                var url = $(this).attr('action-url');
//                 console.log('clicked');
                $('#linkToUnLockInvoice').attr('href', url);
            });

            $('.lockButton1').on('click', function () {
                var url = $(this).attr('action-url');
                // console.log('clicked');
                $('#linkToLockInvoice').attr('href', url);

                var url = $(this).attr('edit-invoice-number-href');
                $('#input-edit-invoice-number-href').attr('action', url);

                var id = $(this).attr('invoice-id');
                $('#invoice-id').attr('invoice-id', id);


                $('#currentInvoiceNumber').text('');
                $.ajax({
                    type: 'get',
                    url: '{!!  route('client.invoices.getLastFiveInvoices') !!}',
                    dataType: "json",
                    success: function (data) {
//                        console.log(data);

                        var html = '';
                        for (x in data) {
                            //console.log(data[x].number);


                            html += "<tr>";
                            html += "<td class='col-xs-3 col-sm-2 col-md-2 col-lg-2'>" + data[x].number + "</td>";
                            html += "<td class='col-xs-3 col-sm-2 col-md-2 col-lg-2'>" + data[x].date + "</td>";

                            var companyName = data[x].partner.name;
                            companyName = companyName.replace("Sabiedrība ar ierobežotu atbildību", "SIA");
                            companyName = companyName.replace("Akciju sabiedrība", "A/S");

                            html += "<td class=''>" + companyName + "</td>";
                        }

                        $('#last5invoices').text('');
                        $('#last5invoices').append(html);
                    }
                });


                var currentInvoiceUrl = $(this).attr('current-invoice-href');
                $.ajax({
                    type: 'get',
                    url: currentInvoiceUrl,
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        if (data.number.length > 0) {
                            $('#currentInvoiceNumber').append(data.number);
                        } else {
                            //$('#currentInvoiceNumber').text(' no number');
                        }
                    }
                });


            });
            $('#show-edit-option').on('click', function () {

//                console.log('#show-edit-option is clicked')
                $('#input-edit-invoice-number').val($('#currentInvoiceNumber').text());
                $('#edit-invoice-number').toggle();

                $('#currentInvoiceNumber').text();
            });

            $(document).on('click', '#update-invoice-button', function () {

//            $('#update-invoice-button').on('click', function () {
                var invoiceId = $('#invoice-id').attr('invoice-id');
                var number = $('#input-edit-invoice-number').val();
                //var url = $('#input-edit-invoice-number-href').attr('action');
                var url = "{{ route('client.invoices.updateInvoiceNumber', '') }}";
                url += '/' + invoiceId;

                console.log('url0: ' + url);


                $.ajax({
                    type: 'get',
                    url: url,
                    dataType: "json",
                    data: {number: number},
                    beforeSend: function () {
                        $(".loading").show();
                    },
                    success: function (data) {
                        console.log(data.number);
                        $('#currentInvoiceNumber').text('');
                        $('#currentInvoiceNumber').append(data.number + '&nbsp;');
                        $('#td' + invoiceId).text('');
                        $('#td' + invoiceId).append(data.number);
                        $('#edit-invoice-number').toggle();
                        $(".loading").hide();
                    }
                });
            });
        });
    </script>

@stop

