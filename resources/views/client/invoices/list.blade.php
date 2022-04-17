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
    <livewire:invoice-list />
@stop

@section('modals')
    @parent
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
    <div id="myModalShow____" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
{{--                    <button type="button" class="close" data-dismiss="modal">&times;</button>--}}
{{--                    <h4 class="modal-title">Confirm</h4>--}}

{{--                    <div class="modal-header">--}}
                        <h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
{{--                    </div>--}}
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

@section('modals')
    @parent

    {{--  Modal3  invoice (show in html of save to file) --}}

{{--    <x-modal id="myModalShow" title="Confirm" saveButtonTitle="Continue" saveButtonId="myModalShowContinue">--}}
{{--        <h6>Select locale:</h6>--}}
{{--        <div class="form-check">--}}
{{--            <input class="form-check-input" type="radio" name="selectedLocale" value="lv" id="localeLv"  @if(($invoicePrintLocale ?? null) =='lv') checked="checked" @endif>--}}
{{--            <label class="form-check-label" for="localeLv">LV</label>--}}
{{--        </div>--}}
{{--        <div class="form-check">--}}
{{--            <input class="form-check-input" type="radio" name="selectedLocale" value="en" id="localeEn" @if(($invoicePrintLocale ?? null) == 'en') checked="checked" @endif>--}}
{{--            <label class="form-check-label" for="localeEn">EN</label>--}}
{{--        </div>--}}
{{--        <hr>--}}

{{--        <h6>Select action:</h6>--}}
{{--        <div class="form-check">--}}
{{--            <input class="form-check-input" type="radio" name="selectedAction" value="html" id="actionHtml" checked="checked">--}}
{{--            <label class="form-check-label" for="actionHtml">Show HTML</label>--}}
{{--        </div>--}}
{{--        <div class="form-check">--}}
{{--            <input class="form-check-input" type="radio" name="selectedAction" value="pdf" id="actionPdf">--}}
{{--            <label class="form-check-label" for="actionPdf">download PDF</label>--}}
{{--        </div>--}}
{{--    </x-modal>--}}
@stop

@section('js')
    @parent
    <script>
        let selectedLocale = 'lv';
        let selectedAction = 'html';
        let url = '';
        let actionUrl = '';

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

        // $('#myModalShow button').on('click', function () {
        //     console.log('here 111');
        //     $('#myModalShow').modal('hide');
        // });

        $('input[name="selectedLocale"], input[name="selectedAction"]').on('change', function () {
            selectedLocale = $('input[name=selectedLocale]:checked', '#myModalShow').val()
            selectedAction = $('input[name=selectedAction]:checked', '#myModalShow').val()
            actionUrl = url + '?type=' + selectedAction + '&locale=' + selectedLocale;
            console.log(selectedLocale);
            console.log(selectedAction);
            console.log(actionUrl);
        });

        $('.showButton1').on('click', function () {
            console.log('.showButton1 clicked');

            url = $(this).attr('action-url');

            $('input[name="selectedLocale"]').trigger('change');
            $('input[name="selectedAction"]').trigger('change');

            console.log(url);
            // $('#linkToShowInvoice0').attr('href', url + '?type=html');
            // $('#linkToShowInvoice1').attr('href', url + '?');
            // $('input[name="locale"]').trigger('change');

        });

        $('#myModalShowContinue').on('click', function(){
            let el = document.createElement('a');
            el.setAttribute('href', actionUrl);

            alert(el.outerHTML);
        });



    </script>

@stop

@section('js')
    @parent
    <script type="text/javascript">
        $(document).ready(function () {

            console.log('A1');

            $('[data-toggle1="tooltip"]').tooltip();

            $('.showActionOptions').on('click', function () {

                let that = this;

                let selectedShouldBeHidden = false;

                if(!$(that).next().hasClass('d-none')){
                    selectedShouldBeHidden = true;
                }

                $('.showActionOptions').each(function(){
                    $(this).next().addClass('d-none');
                    $(this).removeClass('fa-plus-square-o').removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
                });

                if(selectedShouldBeHidden){
                    return;
                }

                $(that).next().removeClass('d-none');
                $(that).removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
            });

            // $('.line').on('click', function(){
            //     let that = this;
            //     let shouldStayHidden = !$(that).next().hasClass('d-none')
            //
            //     $('.actions').addClass('d-none');
            //     if(shouldStayHidden){
            //         return;
            //     }
            //     $(that).next().removeClass('d-none');
            // });


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

            $('#dp3').datepicker({
                format: 'dd.mm.yyyy',
                weekStart: 1,
                todayBtn: "linked",
                todayHighlight: true,
                autoclose: true,
                clearBtn: true,
//                calendarWeeks: true,
                daysOfWeekDisabled: [],
                daysOfWeekHighlighted: [0, 6],
            });

            $('#dp4').datepicker({
                format: 'dd.mm.yyyy',
                weekStart: 1,
                todayBtn: "linked",
                todayHighlight: true,
                autoclose: true,
                clearBtn: true,
//                calendarWeeks: true,
                daysOfWeekDisabled: [],
                daysOfWeekHighlighted: [0, 6],
            });

            $('#filter_icon, #filter_open').on('click', function () {
                let modalHeight = parseInt($('#filter').css('height'));
                modalHeight -= 20;
                $('#filter').toggleClass('d-none');
                $('#filter_open').toggleClass('d-none');
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
