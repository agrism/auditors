@extends('client.layout.master')

@section('style')
    <style>
        #filter .panel-body table tr {
            border-bottom: 2px solid transparent;
        }
    </style>
    {{--    @parent--}}
@stop

@section('content')

    <div id="filter_open" class="fa fa-filter fa-1x hidden"
         style="position:fixed;background-color: greenyellow;z-index:100;right:0;bottom: 0px; padding: 11px;border: 1px solid black;cursor: pointer">
    </div>
    <div id="filter" class="panel panel-default"
         style="z-index: 100;right:0px;position:fixed;bottom: 0px;border: 1px solid black">
        <div class="panel-heading" style="background-color: greenyellow">
            Filter
            <div id="filter_icon" class="fa fa-arrow-right pull-right fa-1x" style="cursor: pointer"></div>
        </div>

        <div class="panel-body">
            <div class="table-responsive">
                {!! Form::open(['method'=>'get', 'route'=>'client.invoices.index']) !!}
                <table>
                    <tr>
                        <td width="80">Date:</td>
                        <td>
                            <table>
                                <tr>
                                    <td>
                                        {!! Form::text('filter[date_from]', $params['filter']['date_from'] ?? null, ['class' => 'form-control input-sm', 'id'=>'dp1', 'autocomplete'=>'off']) !!}

                                    </td>
                                    <td>
                                        {!! Form::text('filter[date_to]', $params['filter']['date_to'] ?? null, ['class' => 'form-control input-sm', 'id'=>'dp2', 'autocomplete'=>'off']) !!}

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td>Type</td>
                        <td>{!! Form::select('filter[invoicetype_id]', $invoicetypes->pluck('title', 'id'), isset($params) && isset($params['filter']['invoicetype_id']) && $params['filter']['invoicetype_id'] !='' ? $params['filter']['invoicetype_id'] : null, ['class'=>'form-control input-sm', 'placeholder'=>'filter by type']) !!}</td>
                    </tr>
                    <tr>
                        <td>Struct</td>
                        <td>{!! Form::select('filter[structuralunit_id]', $structuralunits->pluck('title', 'id'), isset($params) && isset($params['filter']['structuralunit_id']) && $params['filter']['structuralunit_id'] !='' ? $params['filter']['structuralunit_id'] : null, ['class'=>'form-control input-sm', 'placeholder'=>'filter by struct.unit']) !!}</td>
                    </tr>
                    <tr>
                        <td>Partner</td>
                        <td>{!! Form::select('filter[partner_id]', $partners->pluck('name', 'id'), isset($params) && isset($params['filter']['partner_id']) && $params['filter']['partner_id'] !='' ? $params['filter']['partner_id'] : null, ['class'=>'form-control input-sm', 'placeholder'=>'filter by partner']) !!}</td>
                    </tr>
                    <tr>
                        <td>Details</td>
                        <td>
                            {!! Form::text('filter[details_self]', isset($params) && isset($params['filter']['details_self']) && $params['filter']['details_self'] !=''  ?  $params['filter']['details_self'] : null, ['class'=>'form-control input-sm', 'placeholder'=>'text']) !!}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-valign-center">
                            {{-- <a href="{{ url(route('client.invoices.index'))}}"><div class="btn btn-info  fa   fa-filter"></div></a>  --}}

                            <button class="btn btn-default btn-lg fa fa-filter "></button>
                            <div id="clear_filter" class="btn btn-default btn-lg fa fa-remove" alt="clear filter"></div>
                        </td>
                    </tr>
                </table>
                {!! Form::close() !!}
            </div>

        </div>
    </div>


    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Invoice list <a href="{{ url(route('client.invoices.create'))}}"
                                    class="btn btn-success btn-xs fa-plus fa"></a></h4>
                <a href="{{route('client.invoices.index', ['export'=> 'xls'])}}">
                    <div class="fa fa-file-excel-o"
                         style="position:fixed;right:0px;padding: 11px; border: 1px solid black;cursor: pointer; background-color: #b6e8fa"></div>
                </a>
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
{{--                            <th>action</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($invoices as $invoice)
                            <tr class="line {{ (preg_match('/copy/',$invoice->number)) ? 'danger' : null }}" style="cursor: pointer">
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

                            </tr>
                            <tr class="hidden actions" style="background-color: #c4c4c4">
                                <td colspan="100">
                                    <div class="actionOptionns text-center" style="z-index: 2; position:relative;">

                                        <div class="text-warning fa fa-file fa-2x showButton1"
                                                style="cursor: pointer"
                                                data-toggle1="tooltip" title="{{ _("View") }}" data-placement="top"
                                                data-toggle="modal"
                                                data-target="#myModalShow"
                                                action-url="{{ url(route('client.invoices.show', $invoice->id))}}"></div>

                                        <div class="text-info fa-2x fa fa-copy copyButton1"
                                                style="cursor: pointer"
                                                data-toggle1="tooltip" title="{{ _("Copy") }}" data-placement="top"
                                                data-toggle="modal"
                                                data-target="#myModalCopy"
                                                action-url="{{ url(route('client.invoices.copy', $invoice->id))}}"></div>

                                        @if($invoice->is_locked)

                                            @if(\Auth::user()->isAdmin())
                                                <div class="text-info fa fa-lock fa-2x unlockButton1"
                                                        style="cursor: pointer"
                                                        data-toggle1="tooltip" title="{{ _("UnLock") }}"
                                                        data-placement="top"
                                                        data-toggle="modal"
                                                        data-target="#myModalUnLock"
                                                        invoice-id="{{ $invoice->id }}"
                                                        current-invoice-href="{{route('client.invoices.getCurrentInvoice', $invoice->id ) }}"
                                                        edit-invoice-number-href="{{route('client.invoices.updateInvoiceNumber', $invoice->id ) }}"
                                                        action-url="{{ url(route('client.invoices.unlock', $invoice->id))}}"></div>
                                            @else
                                                <div class="text-info fa fa-2x  fa-lock"></div>
                                            @endif
                                        @else

                                            <div class="text-info fa-md fa fa-unlock fa-2x lockButton1"
                                                    style="cursor: pointer"
                                                    data-toggle1="tooltip" title="{{ _("Lock") }}" data-placement="top"
                                                    data-toggle="modal"
                                                    data-target="#myModalLock"
                                                    invoice-id="{{ $invoice->id }}"
                                                    current-invoice-href="{{route('client.invoices.getCurrentInvoice', $invoice->id ) }}"
                                                    edit-invoice-number-href="{{route('client.invoices.updateInvoiceNumber', $invoice->id ) }}"
                                                    action-url="{{ url(route('client.invoices.lock', $invoice->id))}}"></div>

                                            {{-- 	<a href="{{ url(route('client.invoices.lock', $invoice->id))}}"><div class="btn btn-info btn-xs fa   fa-unlock"></div></a> --}}

                                            <a href="{{ url(route('client.invoices.edit',  $invoice->id))}}">
                                                <div class="text-success fa-md fa-edit fa fa-2x"
                                                     data-toggle1="tooltip" title="{{_("Edit")}}" data-placement="top"
                                                ></div>
                                            </a>

                                            <div type="button"
                                                    style="cursor: pointer"
                                                    class="text-danger fa-remove fa fa-2x deleteButton1"
                                                    data-toggle1="tooltip" title="{{_("Delete")}}" data-placement="top"
                                                    data-toggle="modal"
                                                    data-target="#myModal"
                                                    action-url="{{ url(route('client.invoices.destroy',  [$invoice->id,'method'=>'delete']))}}"></div>

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
                    <h3>What to do with the selected invoice:</h3>
                    <div class="radio">
                        <label><input type="radio" name="locale" value="lv" checked>LV</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="locale" value="en">EN</label>
                    </div>
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

                let that = this;

                let selectedShouldBeHidden = false;

                if(!$(that).next().hasClass('hide')){
                    selectedShouldBeHidden = true;
                }

                $('.showActionOptions').each(function(){
                    $(this).next().addClass('hide');
                    $(this).removeClass('fa-plus-square-o').removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
                });

                if(selectedShouldBeHidden){
                    return;
                }

                $(that).next().removeClass('hide');
                $(that).removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
            });

            $('.line').on('click', function(){
                let that = this;
                let shouldStayHidden = !$(that).next().hasClass('hidden')

                $('.actions').addClass('hidden');
                if(shouldStayHidden){
                    return;
                }
                $(that).next().removeClass('hidden');
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
                $('#linkToShowInvoice0').attr('href', url + '?type=html');
                $('#linkToShowInvoice1').attr('href', url + '?');
                $('input[name="locale"]').trigger('change');
            });

            $('input[name="locale"]').on('change', function () {
                let locale = $('input[name=locale]:checked', '#myModalShow').val()
                console.log(locale);
                updateLocale('#linkToShowInvoice0', locale);
                updateLocale('#linkToShowInvoice1', locale);
            });

            function updateLocale(element, locale){
                let link1 = $(element).attr('href');
                let link2 = [];
                link1.split('&').forEach(function (segment) {
                    if (segment.split('=')[0] != 'locale') {
                        link2.push(segment);
                    }
                });
                link2.push('locale=' + locale);
                $(element).attr('href', link2.join('&'))
            }

            $('#myModalShow button').on('click', function () {
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

            $('#dp1').datepicker({
                format: 'dd.mm.yyyy',
                weekStart: 1,
                todayBtn: "linked",
                todayHighlight: true,
                autoclose: true,
//                calendarWeeks: true,
                daysOfWeekDisabled: [],
                daysOfWeekHighlighted: [0, 6],
            });

            $('#dp2').datepicker({
                format: 'dd.mm.yyyy',
                weekStart: 1,
                todayBtn: "linked",
                todayHighlight: true,
                autoclose: true,
//                calendarWeeks: true,
                daysOfWeekDisabled: [],
                daysOfWeekHighlighted: [0, 6],
            });

            $('#filter_icon, #filter_open').on('click', function () {
                let modalHeight = parseInt($('#filter').css('height'));
                modalHeight -= 20;
                $('#filter').toggleClass('hidden');
                $('#filter_open').toggleClass('hidden');
                $('#filter_open').css('bottom', modalHeight + 'px');
            })

            $('#clear_filter').on('click', function () {
                $(this).closest('form').find('input, select').each(function () {
                    $(this).val(null);
                });
            });

        });
    </script>

@stop

