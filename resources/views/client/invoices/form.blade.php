@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    {!! Form::label('date', 'Date', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-2">
        {!! Form::text('date', isset($invoice) ? $invoice['date'] : \Carbon\Carbon::now()->format('d.m.Y') , ['class'=>'form-control', 'placeholder'=>'Input date', 'id'=>'dp1', 'readonly'] ) !!}
    </div>

    {!! Form::label('invoicetype_id', 'Invoice Type:', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::select('invoicetype_id', isset($invoicetypes) ? $invoicetypes->pluck('title', 'id') : [] , isset($invoice) ? $invoice['invoicetype_id'] : null , ['class'=>'form-control'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('payment_date', 'Payment Date', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-2">
        {!! Form::text('payment_date', isset($invoice) ? $invoice['payment_date'] : \Carbon\Carbon::now()->format('d.m.Y')  , ['class'=>'form-control', 'placeholder'=>'Input payment date', 'id'=>'dp2', 'readonly'] ) !!}
    </div>

    {!! Form::label('structuralunit_id', 'Structural Unit:', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::select('structuralunit_id', isset($structuralunits) ? $structuralunits->pluck('title', 'id') : [] , isset($invoice) ? $invoice['structuralunit_id'] : null , ['class'=>'form-control'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('number', 'No', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-2">
        {!! Form::text('number', isset($invoice) ? $invoice['number'] : null , ['class'=>'form-control', 'placeholder'=>'Input No.'] ) !!}
    </div>

    {!! Form::label('details_self', 'Details invisible:', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-6">
        {!! Form::text('details_self', isset($invoice) ? $invoice['details_self'] : null , ['class'=>'form-control', 'placeholder'=>'Input details obly for self.'] ) !!}
    </div>

</div>

<div class="form-group">
    {!! Form::label('vat_number', 'Vat No', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-2">
        {!! Form::select('vat_number',isset($companyVatNumbers) ? $companyVatNumbers->pluck('vat_number', 'vat_number') : [] ,isset($invoice) ? $invoice['vat_number'] : null , ['class'=>'form-control', 'placeholder'=>'Select optional VAT no'] ) !!}
    </div>
    <div>
        {!! Form::label('currency_id', 'Currency:', ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-2">
            {!! Form::select('currency_id', $currencies ,isset($invoice) ? $invoice['currency_id'] : null , ['class'=>'form-control', 'id'=>'currency_id'] ) !!}
        </div>

        {!! Form::label('currency_rate', 'Rate:', ['class'=>'col-sm-1 control-label']) !!}
        <div class="col-sm-3">
            {!! Form::text('currency_rate', isset($invoice) ? $invoice['currency_rate'] : 1 , ['class'=>'form-control', 'placeholder'=>'Currency rate.', 'id'=>'currency_rate'] ) !!}
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('partner_id', 'Partner', ['class'=>'col-sm-2 control-label']) !!}


    <div class="col-sm-10">
        <div class="input-group col-sm-12 col-md-5">
            {!! Form::select('partner_id', $partners ,isset($invoice) ? $invoice['partner_id'] : null , ['class'=>'form-control', 'placeholder'=>'Select partner '] ) !!}
        </div>
    </div>

</div>

<div class="form-group">
    {!! Form::label('bank_id', 'Optional Payment receiver', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        <div class="input-group col-sm-12 col-md-5">
            {!! Form::select('bank_id', $bank ,isset($selectedBank) ? $selectedBank['id'] : null , ['class'=>'form-control', 'placeholder'=>'Select optional payment receiver'] ) !!}
        </div>
    </div>
</div>

{{--<hr></hr>--}}

{{--<div class="form-group">--}}
{{--    {!! Form::label('currency_id', 'Currency', ['class'=>'col-sm-2 control-label']) !!}--}}
{{--    <div class="col-sm-2">--}}
{{--        {!! Form::select('currency_id', $currencies ,isset($invoice) ? $invoice['currency_id'] : null , ['class'=>'form-control', 'id'=>'currency_id'] ) !!}--}}
{{--    </div>--}}
{{--    <div class="col-sm-2">--}}
{{--        {!! Form::text('currency_rate', isset($invoice) ? $invoice['currency_rate'] : 1 , ['class'=>'form-control', 'placeholder'=>'Currency rate.', 'id'=>'currency_rate'] ) !!}--}}
{{--    </div>--}}
{{--</div>--}}


<div id="ppr_fields" class="@if($invoice['invoicetype_id'] != 3) hidden @endif" style="background-color: #82e982 !important; padding-bottom: 2px">
<hr>
<div class="form-group">
    {!! Form::label('goods_out_address', 'Goods delivery from/to', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-5">
        {!! Form::text('goods_address_from', isset($invoice) ? $invoice['goods_address_from'] : null , ['class'=>'form-control', 'placeholder'=>'Goods delivered from'] ) !!}
    </div>
    <div class="col-sm-5">
        {!! Form::text('goods_address_to', isset($invoice) ? $invoice['goods_address_to'] : null , ['class'=>'form-control', 'placeholder'=>'Goods delivered to'] ) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('goods_deliverer', 'Carrier', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('goods_deliverer', isset($invoice) ? $invoice['goods_deliverer'] : null , ['class'=>'form-control', 'placeholder'=>'Organizācija, Auto Nr, šoferis'] ) !!}
    </div>
</div>
</div>


<hr>
<div class="form-group">
    {!! Form::label('details', 'Details', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('details', isset($invoice) ? $invoice['details'] : null , ['class'=>'form-control', 'placeholder'=>'Details.'] ) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('details1', 'Details other:', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('details1', isset($invoice) ? $invoice['details1'] : null , ['class'=>'form-control', 'placeholder'=>'Details.'] ) !!}
    </div>
</div>

<hr>

<table class="table table-hover table-condensed ">
    <thead>
    <th width="">Code</th>
    <th width="">Service/good</th>
    <th width="120px">Unit</th>
    <th width="120">Quentity</th>
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
                    {!! Form::text('code[]', isset($line) ? $line['code'] : null , ['style'=>'min-width:50px','class'=>'form-control input-sm line_code line-1 text-right', 'placeholder'=>'code'] ) !!}
                </td>
                <td>
                    {!! Form::hidden('line_id[]', $line->id) !!}
                    {!! Form::textarea('title[]', isset($line) ? $line['title'] : null , ['size'=>'100%xAuto', 'style'=>'height: 30px; min-width:200px','class'=>'form-control input-sm line_title line-1', 'placeholder'=>'title'] ) !!}
                </td>
                <td>
                    {!! Form::select('unit_id[]', $units->pluck('name','id'), isset($line) ? $line['unit_id'] : null , ['style'=>'min-width:80px','class'=>'form-control input-sm line_unit line-1 text-right'] ) !!}
                </td>

                <td>
                    {!! Form::text('quantity[]', isset($line) ? $line['quantity'] : null , ['style'=>'min-width:80px','class'=>'form-control input-sm line_quantity line-1 text-right', 'placeholder'=>'quantity'] ) !!}
                </td>
                <td>
                    {!! Form::text('price[]', isset($line) ? $line['price'] : null , ['style'=>'min-width:80px','class'=>'form-control input-sm line_price line-1 text-right', 'placeholder'=>'price'] ) !!}
                </td>
                <td class="currencyData">
                    {!! Form::text('total[]',  isset($line) ? ROUND($line['price'] * $line['quantity'], 2)  : null , ['style'=>'min-width:80px', 'class'=>'form-control input-sm line_total line-1 text-right ', 'placeholder'=>'total', 'readonly'] ) !!}
                </td>
                <td>
                    {!! Form::text('total_base_currency[]',  isset($line) ? ROUND($line['price'] * $line['quantity'] * $line['currency_rate'], 2)  : null , ['style'=>'min-width:80px', 'class'=>'form-control input-sm line_total_base_currency line-1 text-right', 'placeholder'=>'total_base_currency', 'readonly'] ) !!}
                </td>
                <td>
                    {!! Form::select('vat_id[]', $vats->pluck('name', 'id') ,isset($line) ? $line['vat_id'] : null , ['style'=>'min-width:70px', 'class'=>'form-control input-sm line_vat_id line-1'] ) !!}
                </td>
                <td>
                    <div class="btn btn-xs btn-danger fa fa-remove remove-line"></div>
                </td>
            </tr>

        @endforeach
    @endif
    {{-- end foreach line data from db  --}}


    {{--  empty line starts --}}
    <tr id="line-empty-div" class="hidden">
        <td>
            {!! Form::text('code[]', null , ['style'=>'min-width:50px','class'=>'form-control input-sm line_code line-1 text-right', 'placeholder'=>'code'] ) !!}
        </td>
        <td>
            {!! Form::hidden('line_id[]', null) !!}
            {!! Form::textarea('title[]', null , ['size'=>'100%xAuto', 'style'=>'height: 30px', 'class'=>'form-control input-sm line_title line-1', 'placeholder'=>'title'] ) !!}
        </td>
        <td>
            {!! Form::select('unit_id[]', $units->pluck('name', 'id') , $units[0]->id , ['class'=>'form-control input-sm line_unit line-1 text-right'] ) !!}
        </td>
        <td>
            {!! Form::text('quantity[]', null , ['class'=>'form-control input-sm line_quantity line-1 text-right', 'placeholder'=>'quantity'] ) !!}
        </td>
        <td>
            {!! Form::text('price[]', null , ['class'=>'form-control input-sm line_price line-1 text-right', 'placeholder'=>'price'] ) !!}
        </td>
        <td class="currencyData">
            {!! Form::text('total[]', null , ['class'=>'form-control input-sm line_total line-1 text-right', 'placeholder'=>'total', 'readonly'] ) !!}
        </td>
        <td>
            {!! Form::text('total_base_currency[]', null , ['class'=>'form-control input-sm line_total_base_currency line-1 text-right', 'placeholder'=>'total', 'readonly'] ) !!}
        </td>
        <td>
        {!! Form::select('vat_id[]', $vats->pluck('name', 'id') ,$vats[0]->id , ['class'=>'form-control input-sm line_vat_id line-1'] ) !!}
        <td>
            <div class="btn btn-xs btn-danger fa fa-remove remove-line"></div>
        </td>
    </tr>

    <tr id="placeNewRow"></tr>
    {{--  empty line ends --}}



    {{-- here starts subTotals, tax, total- by tax rates! --}}
    {{-- rate 21% --}}
    @foreach($vats as $vat)
        <tr class="hidden">
            <td colspan="4" class="text-right">
                {{ 'Total before tax ('.$vat->name.'):' }}
            </td>
            <td class="currencyData">
                {!! Form::text('invoiceBeforeTaxTotal_'.$vat->id, null , ['class'=>'form-control text-right', 'placeholder'=>'', 'id'=>'invoiceBeforeTaxTotal_'.$vat->id, 'readonly'] ) !!}
            </td>
            <td>
                {!! Form::text('invoiceBeforeTaxTotal_base_currency_'.$vat->id, null , ['class'=>'form-control text-right', 'placeholder'=>'', 'id'=>'invoiceBeforeTaxTotal_base_currency_'.$vat->id, 'readonly'] ) !!}
            </td>
        </tr>
        <tr>
            <td colspan="4" class="text-right">
                {{ 'VAT ('.$vat->name.'):' }}
            </td>
            <td class="currencyData">
                {!! Form::text('invoiceVat_'.$vat->id, null , ['class'=>'form-control text-right', 'placeholder'=>'', 'id'=>'invoiceVat_'.$vat->id, 'readonly'] ) !!}
            </td>
            <td>
                {!! Form::text('invoiceVat_base_currency_'.$vat->id, null , ['class'=>'form-control text-right', 'placeholder'=>'', 'id'=>'invoiceVat_base_currency_'.$vat->id, 'readonly'] ) !!}
            </td>
        </tr>

        <tr>
            <td colspan="4" class="text-right">
                {{ 'Total with tax ('.$vat->name.'):' }}
            </td>
            <td class="currencyData">
                {!! Form::text('invoiceTotal_'.$vat->id, null , ['class'=>'form-control text-right', 'placeholder'=>'', 'id'=>'invoiceTotal_'.$vat->id, 'readonly'] ) !!}
            </td>
            <td>
                {!! Form::text('invoiceTotal_base_currency_'.$vat->id, null , ['class'=>'form-control text-right', 'placeholder'=>'', 'id'=>'invoiceTotal_base_currency_'.$vat->id, 'readonly'] ) !!}
            </td>
        </tr>
        <tr class="space hidden">
            <td colspan="5">
                {{-- <hr> --}}
            </td>
        </tr>
    @endforeach

    <tr>
        <td colspan="4" class="text-right">
            {{ 'Total:' }}
        </td>
        <td class="currencyData">
            {!! Form::text('invoiceTotal', null , ['class'=>'form-control text-right', 'placeholder'=>'', 'id'=>'invoiceTotal', 'readonly'] ) !!}
        </td>
        <td>
            {!! Form::text('invoiceTotal_base_currency', null , ['class'=>'form-control text-right', 'placeholder'=>'', 'id'=>'invoiceTotal_base_currency', 'readonly'] ) !!}
        </td>
    </tr>

    </tbody>
</table>

<div class="btn btn-info fa-plus fa" id="addLine"></div>
<hr>

<div class="form-group">
    {!! Form::label('details_bottom1', 'Details1', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('details_bottom1', isset($invoice) ? $invoice['details_bottom1'] : null , ['class'=>'form-control', 'placeholder'=>'Details 1'] ) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('details_bottom2', 'Details2', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('details_bottom2', isset($invoice) ? $invoice['details_bottom2'] : null , ['class'=>'form-control', 'placeholder'=>'Details 2'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('document_signer', 'Document signer', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-4">
        {!! Form::text('document_signer', isset($invoice) ? $invoice['document_signer'] : null , ['class'=>'form-control text-left', 'placeholder'=>'Signer, our name'] ) !!}
    </div>

    {!! Form::label('document_partner_signer', 'Partner signer', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-4">
        {!! Form::text('document_partner_signer', isset($invoice) ? $invoice['document_partner_signer'] : null , ['class'=>'form-control text-left', 'placeholder'=>'Partner signer name'] ) !!}
    </div>
</div>


<div class="form-group">
    {!! Form::label('details_bottom3', 'Details3', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('details_bottom3', isset($invoice) ? $invoice['details_bottom3'] : null , ['class'=>'form-control text-center', 'placeholder'=>'Details 3'] ) !!}
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
                div = $('#line-empty-div').clone().removeClass('hidden');
                div.find("input").val("");
                $('#placeNewRow').before(div);
            });

            $(document.body).on('click', '.remove-line', function () {
                $(this).parent().parent().remove();
                recalculateInvoiceData();

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
                daysOfWeekHighlighted: [0, 6]

            });
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
            }
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

                var lineTotalInCurrency = (quentity * price).toFixed(2);
                $(this).parent().parent().find('.line_total').val(lineTotalInCurrency);

                var lineTotalInBaseCurrency = (lineTotalInCurrency / currencyRate).toFixed(2);
                $(this).parent().parent().find('.line_total_base_currency').val(lineTotalInBaseCurrency);

                invoiceTotatWithOutVatForSpecificVatRateCurrentCurrency[vat_id] += parseFloat(lineTotalInCurrency);
                invoiceTotatWithOutVatForSpecificVatRateBaseCurrency[vat_id] += parseFloat(lineTotalInBaseCurrency);
            });

            var vats = <?php echo json_encode($vats) ?>;


            for (var key in vats) {

                var currencyRate = $('#currency_rate').val();

                beforeTax = invoiceTotatWithOutVatForSpecificVatRateCurrentCurrency[vats[key].id];

                tax = (beforeTax * vats[key].rate).toFixed(2);
                taxBaseCurrency = (tax / currencyRate).toFixed(2);

                total = parseFloat(beforeTax) + parseFloat(tax);
                totalCurrency = (parseFloat(total) / currencyRate).toFixed(2);

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
                    $('#invoiceBeforeTaxTotal_' + vats[key].id).parent().parent().removeClass('hidden');
                    $('#invoiceVat_' + vats[key].id).parent().parent().removeClass('hidden');
                    $('#invoiceTotal_' + vats[key].id).parent().parent().removeClass('hidden').next('tr').removeClass('hidden');
                } else {
                    $('#invoiceBeforeTaxTotal_' + vats[key].id).parent().parent().addClass('hidden');
                    $('#invoiceVat_' + vats[key].id).parent().parent().addClass('hidden');
                    $('#invoiceTotal_' + vats[key].id).parent().parent().addClass('hidden').next('tr').addClass('hidden');
                }
            }

            invoiceTotalCurency_accounting = accounting.formatMoney(invoiceTotalCurency);
            invoiceTotalBaseCurency_accounting = accounting.formatMoney(invoiceTotalBaseCurency);

            $('#invoiceTotal').val(invoiceTotalCurency_accounting);
            $('#invoiceTotal_base_currency').val(invoiceTotalBaseCurency_accounting);


        }

        //        $(document).ready(function () {
        //            $(document.body).on('click', '#partner_edit', function () {
        //                console.log('clisked ...');
        //
        //
        //            });
        //        });
    </script>

    <script>
        $('#invoicetype_id').on('change', function(){
            if($(this).val() == 3){
                $('#ppr_fields').removeClass('hidden');
            } else {
                $('#ppr_fields').addClass('hidden');
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
                                {!! Form::text('name', '11', ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('address', 'Address', ['class'=>'col-sm-2 control-label'])  !!}
                            <div class="col-xs-10">
                                {!! Form::text('address', '11', ['class'=>'form-control ']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('registration_number', 'Reg.No', ['class'=>'col-sm-2 control-label'])  !!}
                            <div class="col-xs-10">
                                {!! Form::text('registration_number', '11', ['class'=>'form-control ']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('vat_number', 'VAT.No', ['class'=>'col-sm-2 control-label'])  !!}
                            <div class="col-xs-10">
                                {!! Form::text('vat_number', '11', ['class'=>'form-control ']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('bank', 'Bank', ['class'=>'col-sm-2 control-label'])  !!}
                            <div class="col-xs-10">
                                {!! Form::text('bank', '11', ['class'=>'form-control ']) !!}
                            </div>
                        </div>


                        <div class="form-group">
                            {!! Form::label('swift', 'SWIFT', ['class'=>'col-sm-2 control-label'])  !!}
                            <div class="col-xs-10">
                                {!! Form::text('swift', '11', ['class'=>'form-control ']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('account_number', 'Account', ['class'=>'col-sm-2 control-label'])  !!}
                            <div class="col-xs-10">
                                {!! Form::text('account_number', '11', ['class'=>'form-control ']) !!}
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

