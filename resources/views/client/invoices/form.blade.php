@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@section('style')
    @parent
    <style>
        .form-group {
            margin-bottom: 0px !important;
        }

        label.custom {
            font-size: 12px;
        }

        .border-lable-flt label, .border-lable-flt > span {
            position: absolute;
            left: 0;
            top: 0;
            cursor: text;
            font-size: 75%;
            opacity: 1;
            -webkit-transition: all .2s;
            transition: all .2s;
            top: -.5em;
            left: 0.75rem;
            z-index: 3;
            line-height: 1;
            padding: 0 1px;
        }

        .border-lable-flt label::after, .border-lable-flt > span::after {
            content: " ";
            display: block;
            position: absolute;
            background: white;
            height: 2px;
            top: 50%;
            left: -.2em;
            right: -.2em;
            z-index: -1;
        }

        .border-lable-flt .form-control::-webkit-input-placeholder {
            opacity: 1;
            -webkit-transition: all .2s;
            transition: all .2s;
        }

        .border-lable-flt .form-control:placeholder-shown:not(:focus)::-webkit-input-placeholder {
            opacity: 0;
        }

        .border-lable-flt .form-control:placeholder-shown:not(:focus) + * {
            font-size: 150%;
            opacity: .5;
            top: .3em;
        }

        .input-group .border-lable-flt {
            display: table-cell;
        }

        .input-group .border-lable-flt .form-control {
            border-radius: 0.25rem;
        }

        .input-group .border-lable-flt:not(:last-child), .input-group .border-lable-flt:not(:last-child) .form-control {
            border-bottom-right-radius: 0;
            border-top-right-radius: 0;
            border-right: 0;
        }

        .input-group .border-lable-flt:not(:first-child), .input-group .border-lable-flt:not(:first-child) .form-control {
            border-bottom-left-radius: 0;
            border-top-left-radius: 0;
        }

        .form-control:focus {
            color: #804000;
            background-color: #fff;
            border-color: #006633;
            box-shadow: 0 0 0 0.2rem rgba(0, 102, 51, 0.25);
        }
    </style>
@stop

    <div class="row">
        <div class="col col-sm-6">
            <label for="date" class="custom text-danger">Date</label>
            <input type="text" name="date"
                   value="{{isset($invoice) ? $invoice['date'] : \Carbon\Carbon::now()->format('d.m.Y') }}"
                   class="form-control form-control-sm" placeholder="Input date" id="dp1" readonly>
        </div>

        <div class="col col-sm-6">
            <label for="invoicetype_id" class="custom">Invoice type</label>
            {!! Form::select('invoicetype_id', isset($invoicetypes) ? $invoicetypes->pluck('title', 'id') : [] , isset($invoice) ? $invoice['invoicetype_id'] : null , ['class'=>'form-control form-control-sm', 'id'=>'invoicetype_id'] ) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <label for="payment_date" class="custom  text-danger">Payment date</label>
            {!! Form::text('payment_date', isset($invoice) ? $invoice['payment_date'] : \Carbon\Carbon::now()->format('d.m.Y')  , ['class'=>'form-control form-control-sm', 'placeholder'=>'Input payment date', 'id'=>'dp2', 'readonly'] ) !!}
        </div>

        <div class="col-sm-6">
            <label for="structuralunit_id" class="custom">Structural unit</label>
            {!! Form::select('structuralunit_id', isset($structuralunits) ? $structuralunits->pluck('title', 'id') : [] , isset($invoice) ? $invoice['structuralunit_id'] : null , ['class'=>'form-control form-control-sm'] ) !!}
        </div>
    </div>


    <div class="row">
        <div class="col-sm-6">
            <label for="number" class="custom border-label-flt  text-danger">Invoice No</label>
            {!! Form::text('number', isset($invoice) ? $invoice['number'] : null , ['class'=>'form-control form-control-sm', 'placeholder'=>'Input No.'] ) !!}
        </div>

        <div class="col-sm-6">
            <label for="details_self" class="custom">Internal comment</label>
            {!! Form::text('details_self', isset($invoice) ? $invoice['details_self'] : null , ['class'=>'form-control form-control-sm', 'placeholder'=>'Input details only for self.'] ) !!}
        </div>
    </div>


    <div class="row">
        <div class="col col-sm-6">
            <label for="vat_number" class="custom  text-danger">Vat No</label>
            {!! Form::select('vat_number',isset($companyVatNumbers) ? $companyVatNumbers->pluck('vat_number', 'vat_number') : [] ,isset($invoice) ? $invoice['vat_number'] : null , ['class'=>'form-control form-control-sm', 'placeholder'=>'Select optional VAT no'] ) !!}
        </div>
        <div class="col col-sm-6">
            <div class="row">
                <div class="col-sm-6" style="">
                    <label for="currency_id" class="custom">Currency</label>
                    {!! Form::select('currency_id', $currencies ,isset($invoice) ? $invoice['currency_id'] : null , ['class'=>'form-control form-control-sm', 'id'=>'currency_id'] ) !!}
                </div>

                <div class="col-sm-6" style="">
                    <label for="currency_rate" class="custom">Rate (currency units per one EUR)</label>
                    {!! Form::text('currency_rate', isset($invoice) ? $invoice['currency_rate'] : 1 , ['class'=>'form-control form-control-sm', 'placeholder'=>'GBP/EUR ~ 0.86 GBP', 'id'=>'currency_rate'] ) !!}
                </div>
            </div>
        </div>
    </div>

<div class="form-group">
    <div class="row">
        <div class="col col-sm-6">
            <label for="partner_id" class="custom  text-danger">Partner</label>
            <livewire:partner-select name="partner_id" :selectedPartnerId="$invoice['partner_id']??null"/>
            {{--            {!! Form::select('partner_id', $partners ,isset($invoice) ? $invoice['partner_id'] : null , ['class'=>'form-control form-control-sm', 'placeholder'=>'Select partner '] ) !!}--}}
        </div>

        <div class="col col-sm-6">
            <label for="bank_id" class="custom">Optional Payment receiver</label>
            {!! Form::select('bank_id', $bank ,isset($selectedBank) ? $selectedBank['id'] : null , ['class'=>'form-control form-control-sm', 'placeholder'=>'Select optional payment receiver'] ) !!}
        </div>
    </div>
</div>

{{--//--}}

<div id="ppr_fields" class="@if(($invoice['invoicetype_id'] ?? 'x')  != 3) d-none @endif">
    <hr>
    <div style="padding-bottom: 2px; border: solid #82e982 3px;margin: 5px 0">
        <div class="row">
            <div class="col-sm-4">
                <label for="goods_address_from" class="custom">Goods delivery From</label>
                {!! Form::text('goods_address_from', isset($invoice) ? $invoice['goods_address_from'] : null , ['class'=>'form-control form-control-sm', 'placeholder'=>'Goods delivered from'] ) !!}
            </div>
            <div class="col-sm-4">
                <label for="goods_address_to" class="custom">Goods delivery To</label>
                {!! Form::text('goods_address_to', isset($invoice) ? $invoice['goods_address_to'] : null , ['class'=>'form-control form-control-sm', 'placeholder'=>'Goods delivered to'] ) !!}
            </div>
            <div class="col-sm-4">
                <label for="goods_deliverer" class="custom">Carrier</label>
                {!! Form::text('goods_deliverer', isset($invoice) ? $invoice['goods_deliverer'] : null , ['class'=>'form-control form-control-sm', 'placeholder'=>'Organizācija, Auto Nr, šoferis'] ) !!}
            </div>
        </div>
    </div>
</div>


<hr>
<div class="form-group">
    <div class="row">
        {{--    {!! Form::label('details', 'Details', ['class'=>'col-sm-2 control-label']) !!}--}}
        <div class="col-sm-6">
            <label for="details" class="custom">Details</label>
            {!! Form::text('details', isset($invoice) ? $invoice['details'] : null , ['class'=>'form-control form-control-sm', 'placeholder'=>'Details.'] ) !!}
        </div>
        <div class="col-sm-6">
            <label for="details1" class="custom">Details other</label>
            {!! Form::text('details1', isset($invoice) ? $invoice['details1'] : null , ['class'=>'form-control form-control-sm', 'placeholder'=>'Details.'] ) !!}
        </div>
    </div>
</div>
<div class="form-group">

</div>

<hr>

<table class="table table-hover table-condensed ">
    <thead>
    <th width="">Code</th>
    <th width="">Service/good</th>
    <th width="120px">Unit</th>
    <th width="120">Quantity</th>
    <th width="120">Price</th>
    <th width="120" class="currencyData">
        <div id="invoice_curency_name"></div>
    </th> {{-- total in currency --}}
    <th width="120">Total EUR</th>
    <th width="90">VAT</th>
    </thead>
    <tbody>

    @if( isset($invoice) )
        {{-- foreach line dtaa from db --}}
        @foreach($invoice->invoiceLines as $line)

            <tr>
                <td>
                    {!! Form::text('code[]', isset($line) ? $line['code'] : null , ['style'=>'min-width:50px','class'=>'form-control form-control-sm input-sm line_code line-1 text-end', 'placeholder'=>'code'] ) !!}
                </td>
                <td>
                    {!! Form::hidden('line_id[]', $line->id) !!}
                    {!! Form::textarea('title[]', isset($line) ? $line['title'] : null , ['size'=>'100%xAuto', 'style'=>'height: 30px; min-width:200px','class'=>'form-control form-control-sm input-sm line_title line-1', 'placeholder'=>'title'] ) !!}
                </td>
                <td>
                    {!! Form::select('unit_id[]', $units->pluck('name','id'), isset($line) ? $line['unit_id'] : null , ['style'=>'min-width:80px','class'=>'form-control form-control-sm input-sm line_unit line-1 text-end'] ) !!}
                </td>

                <td>
                    {!! Form::text('quantity[]', isset($line) ? $line['quantity'] : null , ['style'=>'min-width:80px','class'=>'form-control form-control-sm input-sm line_quantity line-1 text-end', 'placeholder'=>'quantity'] ) !!}
                </td>
                <td>
                    {!! Form::text('price[]', isset($line) ? $line['price'] : null , ['style'=>'min-width:80px','class'=>'form-control form-control-sm input-sm line_price line-1 text-end', 'placeholder'=>'price'] ) !!}
                </td>
                <td class="currencyData">
                    {!! Form::text('total[]',  isset($line) ? ROUND($line['price'] * $line['quantity'], 2)  : null , ['style'=>'min-width:80px', 'class'=>'form-control form-control-sm input-sm line_total line-1 text-end ', 'placeholder'=>'total', 'readonly'] ) !!}
                </td>
                <td>
                    {!! Form::text('total_base_currency[]',  isset($line) ? ROUND($line['price'] * $line['quantity'] * $line['currency_rate'], 2)  : null , ['style'=>'min-width:80px', 'class'=>'form-control form-control-sm input-sm line_total_base_currency line-1 text-end', 'placeholder'=>'total_base_currency', 'readonly'] ) !!}
                </td>
                <td>
                    {!! Form::select('vat_id[]', $vats->pluck('name', 'id') ,isset($line) ? $line['vat_id'] : null , ['style'=>'min-width:70px', 'class'=>'form-control form-control-sm input-sm line_vat_id line-1'] ) !!}
                </td>
                <td>
                    <div class="btn btn-xs btn-danger fa fa-remove remove-line"></div>
                </td>
            </tr>

        @endforeach
    @endif
    {{-- end foreach line data from db  --}}


    {{--  empty line starts --}}
    <tr id="line-empty-div" class="d-none">
        <td>
            {!! Form::text('code[]', null , ['style'=>'min-width:50px','class'=>'form-control form-control-sm input-sm line_code line-1 text-end', 'placeholder'=>'code'] ) !!}
        </td>
        <td>
            {!! Form::hidden('line_id[]', null) !!}
            {!! Form::textarea('title[]', null , ['size'=>'100%xAuto', 'style'=>'height: 30px', 'class'=>'form-control form-control-sm input-sm line_title line-1', 'placeholder'=>'title'] ) !!}
        </td>
        <td>
            {!! Form::select('unit_id[]', $units->pluck('name', 'id') , $units[0]->id , ['class'=>'form-control form-control-sm input-sm line_unit line-1 text-end'] ) !!}
        </td>
        <td>
            {!! Form::text('quantity[]', null , ['class'=>'form-control form-control-sm input-sm line_quantity line-1 text-end', 'placeholder'=>'quantity'] ) !!}
        </td>
        <td>
            {!! Form::text('price[]', null , ['class'=>'form-control form-control-sm input-sm line_price line-1 text-end', 'placeholder'=>'price'] ) !!}
        </td>
        <td class="currencyData">
            {!! Form::text('total[]', null , ['class'=>'form-control form-control-sm input-sm line_total line-1 text-end', 'placeholder'=>'total', 'readonly'] ) !!}
        </td>
        <td>
            {!! Form::text('total_base_currency[]', null , ['class'=>'form-control form-control-sm input-sm line_total_base_currency line-1 text-end', 'placeholder'=>'total', 'readonly'] ) !!}
        </td>
        <td>
        {!! Form::select('vat_id[]', $vats->pluck('name', 'id') ,$vats[0]->id , ['class'=>'form-control form-control-sm input-sm line_vat_id line-1'] ) !!}
        <td>
            <div class="btn btn-xs btn-danger fa fa-remove remove-line"></div>
        </td>
    </tr>

    <tr id="placeNewRow"></tr>
    {{--  empty line ends --}}



    {{-- here starts subTotals, tax, total- by tax rates! --}}
    {{-- rate 21% --}}
    @foreach($vats as $vat)
        <tr class="d-none">
            <td colspan="5" class="text-end">
                {{ 'Total before tax ('.$vat->name.'):' }}
            </td>
            <td class="currencyData">
                {!! Form::text('invoiceBeforeTaxTotal_'.$vat->id, null , ['class'=>'form-control form-control-sm text-end', 'placeholder'=>'', 'id'=>'invoiceBeforeTaxTotal_'.$vat->id, 'readonly'] ) !!}
            </td>
            <td>
                {!! Form::text('invoiceBeforeTaxTotal_base_currency_'.$vat->id, null , ['class'=>'form-control form-control-sm text-end', 'placeholder'=>'', 'id'=>'invoiceBeforeTaxTotal_base_currency_'.$vat->id, 'readonly'] ) !!}
            </td>
        </tr>
        <tr>
            <td colspan="5" class="text-end">
                {{ 'VAT ('.$vat->name.'):' }}
            </td>
            <td class="currencyData">
                {!! Form::text('invoiceVat_'.$vat->id, null , ['class'=>'form-control form-control-sm text-end', 'placeholder'=>'', 'id'=>'invoiceVat_'.$vat->id, 'readonly'] ) !!}
            </td>
            <td>
                {!! Form::text('invoiceVat_base_currency_'.$vat->id, null , ['class'=>'form-control form-control-sm text-end', 'placeholder'=>'', 'id'=>'invoiceVat_base_currency_'.$vat->id, 'readonly'] ) !!}
            </td>
        </tr>

        <tr>
            <td colspan="5" class="text-end">
                {{ 'Total with tax ('.$vat->name.'):' }}
            </td>
            <td class="currencyData">
                {!! Form::text('invoiceTotal_'.$vat->id, null , ['class'=>'form-control form-control-sm text-end', 'placeholder'=>'', 'id'=>'invoiceTotal_'.$vat->id, 'readonly'] ) !!}
            </td>
            <td>
                {!! Form::text('invoiceTotal_base_currency_'.$vat->id, null , ['class'=>'form-control form-control-sm text-end', 'placeholder'=>'', 'id'=>'invoiceTotal_base_currency_'.$vat->id, 'readonly'] ) !!}
            </td>
        </tr>
        <tr class="space d-none">
            <td colspan="5">
                {{-- <hr> --}}
            </td>
            <td class="currencyData"></td>
            <td></td>
        </tr>
    @endforeach

    <tr>
        <td colspan="5" class="text-end">
            {{ 'Total:' }}
        </td>
        <td class="currencyData">
            {!! Form::text('invoiceTotal', null , ['class'=>'form-control form-control-sm text-end', 'placeholder'=>'', 'id'=>'invoiceTotal', 'readonly'] ) !!}
        </td>
        <td>
            {!! Form::text('invoiceTotal_base_currency', null , ['class'=>'form-control form-control-sm text-end', 'placeholder'=>'', 'id'=>'invoiceTotal_base_currency', 'readonly'] ) !!}
        </td>
    </tr>


    {{-- advance payment--}}
    <tr>
        <td colspan="5" class="text-end">
            {{ 'Advance payment:' }}
        </td>
        <td class="currencyData">
            {!! Form::text('invoiceAdvancePayment', null , ['class'=>'form-control form-control-sm text-end', 'placeholder'=>'', 'id'=>'invoiceAdvancePayment', 'readonly'] ) !!}
        </td>
        <td>
            {!! Form::text('invoiceAdvancePayment_base_currency', null , ['class'=>'form-control form-control-sm text-end', 'placeholder'=>'', 'id'=>'invoiceAdvancePayment_base_currency', 'readonly'] ) !!}
        </td>
    </tr>
    {{-- payable --}}
    <tr>
        <td colspan="5" class="text-end">
            {{ 'Payable:' }}
        </td>
        <td class="currencyData">
            {!! Form::text('invoicePayable', null , ['class'=>'form-control form-control-sm text-end', 'placeholder'=>'', 'id'=>'invoicePayable', 'readonly'] ) !!}
        </td>
        <td>
            {!! Form::text('invoicePayable_base_currency', null , ['class'=>'form-control form-control-sm text-end', 'placeholder'=>'', 'id'=>'invoicePayable_base_currency', 'readonly'] ) !!}
        </td>
    </tr>

    </tbody>
</table>

<div class="btn btn-info fa-plus fa" id="addLine"></div>


<hr>

<div id="place_for_prepayments"></div>
<div class="btn btn-info btn-xs mt-3" id="addRepaymentLine">Add received pre-payment</div>
<hr>

<div class="row">
    <div class="col-sm-12">
        <label for="details_bottom1" class="custom">Details1</label>
        {!! Form::text('details_bottom1', isset($invoice) ? $invoice['details_bottom1'] : null , ['class'=>'form-control form-control-sm', 'placeholder'=>'Details 1'] ) !!}
    </div>
    <div class="col-sm-12">
        <label for="details_bottom2" class="custom">Details2</label>
        {!! Form::text('details_bottom2', isset($invoice) ? $invoice['details_bottom2'] : null , ['class'=>'form-control form-control-sm', 'placeholder'=>'Details 2'] ) !!}
    </div>
</div>

    <div class="row">
        <div class="col-sm-6">
            <label for="document_signer" class="custom  text-danger">Document signer</label>
            {!! Form::text('document_signer', isset($invoice) ? $invoice['document_signer'] : null , ['class'=>'form-control form-control-sm text-left', 'placeholder'=>'Signer, our name'] ) !!}
        </div>

        <div class="col-sm-6">
            <label for="document_partner_signer" class="custom">Partner signer</label>
            {!! Form::text('document_partner_signer', isset($invoice) ? $invoice['document_partner_signer'] : null , ['class'=>'form-control form-control-sm text-left', 'placeholder'=>'Partner signer name'] ) !!}
        </div>
    </div>


<div class="row">
    <div class="col-sm-12">
        <label for="details_bottom3" class="custom">Details3</label>
        {!! Form::text('details_bottom3', isset($invoice) ? $invoice['details_bottom3'] : null , ['class'=>'form-control form-control-sm text-center', 'placeholder'=>'Details 3'] ) !!}
    </div>
</div>


@section('js')

    <script type="text/javascript">
        // var invoiceTotatWithOutVatForSpecificVatRateCurrentCurrency = {
        // 	'1': 0.00,
        // 	'2': 0.00,
        // 	'3': 0.00,
        // 	'4': 0.00,
        // 	'5': 0.00
        // };
        // var invoiceTotatWithOutVatForSpecificVatRateBaseCurrency = {
        // 	'1': 0.00,
        // 	'2': 0.00,
        // 	'3': 0.00,
        // 	'4': 0.00,
        // 	'5': 0.00
        // };


        $(document).ready(function () {


            accounting.settings = {
                currency: {
                    symbol: "",   // default currency symbol is '$'
                    format: "%s%v", // controls output: %s = symbol, %v = value/number (can be object: see below)
                    decimal: ".",  // decimal point separator
                    thousand: ",",  // thousands separator
                    precision: 2   // decimal places
                },
                number: {
                    precision: 0,  // default precision on numbers is 0
                    thousand: ",",
                    decimal: "."
                }
            }

            @foreach($invoice->invoiceAdvancePayments ?? [] as $payment)
            addPrepaymentLine("{{$payment->date}}", "{{$payment->amount}}");
            @endforeach


            // calculateTotal();
            recalculateInvoiceData();
            showHideOtherCurrencyData();


            $(document.body).on('change', '.line-1', function () {
                recalculateInvoiceData();
            });

            $(document.body).on('change', '#currency_rate', function () {
                recalculateInvoiceData();

            });

            $(document.body).on('change', '#currency_id', function () {
                recalculateInvoiceData();
                showHideOtherCurrencyData();
                setCurrencyRateForBaseCurrency()
            });

            $('#addLine').on('click', function () {
                div = $('#line-empty-div').clone().removeClass('d-none');
                div.find("input").val("");
                $('#placeNewRow').before(div);
            });

            $(document.body).on('click', '.remove-line', function () {
                // $(this).parent().parent().remove();
                $(this).closest('.row, tr').remove();
                recalculateInvoiceData();

            });

            ['#dp1', '#dp2'].forEach(function (el) {
                initDatepicker(el);
            });

            document.querySelector('#addRepaymentLine').addEventListener('click', function () {
                addPrepaymentLine();
            });


            function addPrepaymentLine(date, amount) {

                if (!date || date == '' || date == 'undefined') {
                    date = "{{date('d.m.Y')}}";
                }

                if (!amount || amount == '' || amount == 'undefined') {
                    amount = "0.00";
                }

                amount = Number.parseFloat(amount).toFixed(2)

                // amount.toFixed(2);

                let id = uid();
                let newLine = document.createElement('div');
                newLine.id = '_' + id;
                newLine.innerHTML = prepaymentLine;

                newLine.querySelector('.date').value = date;

                newLine.querySelector('.date').id = id;
                newLine.querySelector('.amount').value = amount;
                newLine.querySelector('.amount').addEventListener('change', function () {
                    recalculateInvoiceData();
                });
                document.querySelector('#place_for_prepayments').append(newLine);
                initDatepicker('#' + id);

            }

            function initDatepicker(selector) {
                $(selector).datepicker({
                    format: 'dd.mm.yyyy',
                    weekStart: 1,
                    todayBtn: "linked",
                    todayHighlight: true,
                    autoclose: true,
//                calendarWeeks: true,
                    daysOfWeekDisabled: [],
                    daysOfWeekHighlighted: [0, 6],

                })
            }

            function uid() {
                return Date.now().toString(36) + Math.random().toString(36).substr(2);
            }
        });

        // ------------------------------------------------------------------showHideOtherCurrencyData
        function showHideOtherCurrencyData() {
            <?php // if invoice in base currency, do not show seperate currency column ?>
            if ($('#currency_id').val() == 1) {
                $('.currencyData').hide();

            } else {
                $('.currencyData').show();
                var currencyName = $('#currency_id').find("option:selected").text();
                $('#invoice_curency_name').text('Total ' + currencyName);
            }
        }

        function setCurrencyRateForBaseCurrency() {
            if ($('#currency_id').val() == 1) {
                $('#currency_rate').val(1);
                $('#currency_rate').trigger('change');
            }
        }

        function round(number) {
            return Math.round((number + Number.EPSILON) * 100) / 100;
        }

        // ------------------------------------------------------------------------clalculateEachLine
        function recalculateInvoiceData() {

            var invoiceTotatWithOutVatForSpecificVatRateCurrentCurrency = {
                '1': 0,
                '2': 0,
                '3': 0,
                '4': 0,
                '5': 0,
                '6': 0,
                '7': 0,
                '8': 0
            };
            var invoiceTotatWithOutVatForSpecificVatRateBaseCurrency = {
                '1': 0,
                '2': 0,
                '3': 0,
                '4': 0,
                '5': 0,
                '6': 0,
                '7': 0,
                '8': 0
            };

            var invoiceTotalCurency = 0;
            var invoiceTotalBaseCurency = 0;

            $(".line_quantity").each(function () {
                var quentity = $(this).val();
                var price = $(this).parent().parent().find('.line_price').val();
                var vat_id = $(this).parent().parent().find('.line_vat_id').val();
                var currencyRate = $('#currency_rate').val();

                var lineTotalInCurrency = round(quentity * price).toFixed(2);
                $(this).parent().parent().find('.line_total').val(lineTotalInCurrency);

                var lineTotalInBaseCurrency = round(lineTotalInCurrency / currencyRate).toFixed(2);
                $(this).parent().parent().find('.line_total_base_currency').val(lineTotalInBaseCurrency);

                invoiceTotatWithOutVatForSpecificVatRateCurrentCurrency[vat_id] += parseFloat(lineTotalInCurrency);
                invoiceTotatWithOutVatForSpecificVatRateBaseCurrency[vat_id] += parseFloat(lineTotalInBaseCurrency);
            });

            var vats = <?php echo json_encode($vats) ?>;


            for (var key in vats) {

                var currencyRate = $('#currency_rate').val();

                beforeTax = invoiceTotatWithOutVatForSpecificVatRateCurrentCurrency[vats[key].id];

                tax = round((beforeTax * vats[key].rate)).toFixed(2);
                taxBaseCurrency = round(tax / currencyRate).toFixed(2);

                total = parseFloat(beforeTax) + parseFloat(tax);
                totalCurrency = round(parseFloat(total) / currencyRate).toFixed(2);

                <?php /* rounded diference of converting to base currency influence amount before tax! */ ?>
                // beforeTaxBaseCurrency = (beforeTax / currencyRate).toFixed(2) ;
                beforeTaxBaseCurrency = totalCurrency - taxBaseCurrency;

                invoiceTotalCurency += parseFloat(total);
                invoiceTotalBaseCurency += parseFloat(totalCurrency);

                beforeTaxAccounting = accounting.formatMoney(beforeTax);
                taxAccounting = accounting.formatMoney(tax);
                totalAccounting = accounting.formatMoney(total);

                beforeTaxAccounting_baseCurrency = accounting.formatMoney(beforeTaxBaseCurrency);
                taxAccounting_baseCurrency = accounting.formatMoney(taxBaseCurrency);
                totalAccounting_baseCurrency = accounting.formatMoney(totalCurrency);

                $('#invoiceBeforeTaxTotal_' + vats[key].id).val(beforeTaxAccounting);
                $('#invoiceVat_' + vats[key].id).val(taxAccounting);
                $('#invoiceTotal_' + vats[key].id).val(totalAccounting);

                $('#invoiceBeforeTaxTotal_base_currency_' + vats[key].id).val(beforeTaxAccounting_baseCurrency);
                $('#invoiceVat_base_currency_' + vats[key].id).val(taxAccounting_baseCurrency);
                $('#invoiceTotal_base_currency_' + vats[key].id).val(totalAccounting_baseCurrency);

                if (beforeTax !== 0) {
                    $('#invoiceBeforeTaxTotal_' + vats[key].id).parent().parent().removeClass('d-none');
                    $('#invoiceVat_' + vats[key].id).parent().parent().removeClass('d-none');
                    $('#invoiceTotal_' + vats[key].id).parent().parent().removeClass('d-none').next('tr').removeClass('d-none');
                } else {
                    $('#invoiceBeforeTaxTotal_' + vats[key].id).parent().parent().addClass('d-none');
                    $('#invoiceVat_' + vats[key].id).parent().parent().addClass('d-none');
                    $('#invoiceTotal_' + vats[key].id).parent().parent().addClass('d-none').next('tr').addClass('d-none');
                }
            }

            let invoiceTotalCurency_accounting = accounting.formatMoney(invoiceTotalCurency);
            let invoiceTotalBaseCurency_accounting = accounting.formatMoney(invoiceTotalBaseCurency);

            $('#invoiceTotal').val(invoiceTotalCurency_accounting);
            $('#invoiceTotal_base_currency').val(invoiceTotalBaseCurency_accounting);

            let prepaymentAmount = 0;

            document.querySelectorAll('[name^="prePaymentAmount"]').forEach(function (el) {
                prepaymentAmount -= parseFloat(el.value);
            });

            let prepaymentAmount_base_currency = round(prepaymentAmount / currencyRate).toFixed(2);

            let prepaymentAmount_accounting = accounting.formatMoney(prepaymentAmount);
            let prepaymentAmountBaseCurrency_accounting = accounting.formatMoney(prepaymentAmount_base_currency);

            $('#invoiceAdvancePayment').val(prepaymentAmount_accounting);
            $('#invoiceAdvancePayment_base_currency').val(prepaymentAmountBaseCurrency_accounting);

            let payable = invoiceTotalCurency + prepaymentAmount;

            let payable_base_currency = parseFloat(invoiceTotalBaseCurency) + parseFloat(prepaymentAmount_base_currency);

            let payable_accounting = accounting.formatMoney(payable);
            let payableBaseCurrency_accounting = accounting.formatMoney(payable_base_currency);

            $('#invoicePayable').val(payable_accounting);
            $('#invoicePayable_base_currency').val(payableBaseCurrency_accounting);

        }

        //        $(document).ready(function () {
        //            $(document.body).on('click', '#partner_edit', function () {
        //                console.log('clisked ...');
        //
        //
        //            });
        //        });

        let prepaymentLine = `
<div class="row default_advance_payment_form" style="position:relative;">
    <div class="col-sm-6">
        <label for="prePaymentDate" class="custom">Prepayment payment date</label>
        <input type="text"
                name="prePaymentDate[]"
               value=""
               class="form-control form-control-sm date" placeholder="Input date" readonly>
    </div>

    <div class="col-sm-6">
        <label for="prePaymentAmount" class="custom">Prepayment amount</label>
        <div class="input-group">
            <input type="text"
                   value=""
                   name="prePaymentAmount[]"
                   class="form-control form-control-sm amount text-end" placeholder="Input amount"
            >
            <div style="" class="btn btn-xs btn-danger fa fa-remove remove-line"></div>
        </div>
    </div>
</div>`;


    </script>

    <script>
        $('#invoicetype_id').on('change', function () {
            if ($(this).val() == 3) {
                $('#ppr_fields').removeClass('d-none');
            } else {
                $('#ppr_fields').addClass('d-none');
            }
        });

    </script>

@stop

@section('modals')
    <!-- Modal -->
    <div id="partnerModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Partner edit/create form</h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        {{--<h3>Are you sure you want to delete this invoice?</h3>--}}
                        <div class="form-group">

                            {!! Form::label('name', 'Name', ['class'=>'col-sm-2 control-label'])  !!}

                            <div class="col-sm-10">
                                {!! Form::text('name', '11', ['class'=>'form-control form-control-sm']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('address', 'Address', ['class'=>'col-sm-2 control-label'])  !!}
                            <div class="col-xs-10">
                                {!! Form::text('address', '11', ['class'=>'form-control form-control-sm ']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('registration_number', 'Reg.No', ['class'=>'col-sm-2 control-label'])  !!}
                            <div class="col-xs-10">
                                {!! Form::text('registration_number', '11', ['class'=>'form-control form-control-sm ']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('vat_number', 'VAT.No', ['class'=>'col-sm-2 control-label'])  !!}
                            <div class="col-xs-10">
                                {!! Form::text('vat_number', '11', ['class'=>'form-control form-control-sm ']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('bank', 'Bank', ['class'=>'col-sm-2 control-label'])  !!}
                            <div class="col-xs-10">
                                {!! Form::text('bank', '11', ['class'=>'form-control form-control-sm ']) !!}
                            </div>
                        </div>


                        <div class="form-group">
                            {!! Form::label('swift', 'SWIFT', ['class'=>'col-sm-2 control-label'])  !!}
                            <div class="col-xs-10">
                                {!! Form::text('swift', '11', ['class'=>'form-control form-control-sm ']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('account_number', 'Account', ['class'=>'col-sm-2 control-label'])  !!}
                            <div class="col-xs-10">
                                {!! Form::text('account_number', '11', ['class'=>'form-control form-control-sm ']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="#" id="linkToDeleteInvoice">
                        <button type="button" class="btn btn-info">Save</button>
                    </a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>

            </div>

        </div>
    </div>
@stop