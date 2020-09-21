<style>
    .header,
    .footer {
        width: 100%;
        text-align: center;
        position: fixed;
    }

    .header {
        top: 0px;
    }

    .footer {
        bottom: 0px;
    }

    .pagenum:before {
        content: counter(page);
    }

    table, div {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px;
        border-collapse: collapse;

    }

    table tr td {
        vertical-align: top;
    }

    table.nested-table thead tr th {
        border-style: solid solid solid solid;
        border-width: 1px;
        border-color: black;
        /*margin: 10pt;*/
        padding: 8px;
    }

    table.nested-table tbody tr td {
        border-style: solid solid solid solid;
        border-width: 1px;
        border-color: black;
        /*margin: 0pt;*/
        padding: 3px;
    }

    body {
        height: 842px;
        width: 595px;
        /* to centre page on screen*/
        margin-left: 0;
        margin-right: auto;
    }

    .padding10 {
        padding: 10px;
    }

    .padding20 {
        padding: 20px;
    }

    .padding30 {
        padding: 30px
    }

    .pr10 {
        padding-right: 10px
    }

    .pr15 {
        padding-right: 15px
    }

    .pr20 {
        padding-right: 20px
    }

    .pt30 {
        padding-top: 30px;
    }

    .pt50 {
        padding-top: 50px;
    }

    .pb30 {
        padding-bottom: 30px
    }

    .only-left-border {
        border-top-style: none ! important;
        border-right-style: none ! important;
        border-bottom-style: none ! important;
    }

    .no-border {
        /*		border-left-style: none! important;
                border-right-style: none! important;
                border-bottom-style: none! important;*/
        border: none ! important;

    }

    .main-table {
        /*width: 595px;*/
        width: 710px;
        /*width: 100%;*/
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }
</style>


<?php $total_total = 0; ?>


<div style="width:auto; display:none">
    <div style="float:left;width:10%"></div>
    <div style="float:left;width:10%"></div>
</div>


<script type="text/php">
	if (isset($pdf)) { 
        // $size = 10;
        // $text = date('d.m.Y');
        // // $text = {{ $invoice->details_bottom3}};

        // $font = $fontMetrics->get_font("helvetica");
        // $text_height =$fontMetrics->get_font_height($font, $size);
        // $width = $fontMetrics->get_text_width($text, $font, $size);
        // $w = $pdf->get_width() - $width - 15;
        // $y = $pdf->get_height() - $text_height - 15;

        // $pdf->page_text($w, $y, $text, $font, $size);

          // $font =$fontMetrics->get_font("helvetica", "bold");
          // $pdf->page_text(72, 18, "Header: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));

          $size = 9;
          $text = "Page:{PAGE_NUM} of {PAGE_COUNT} ";

          $font = $fontMetrics->get_font("helvetica");
          $text_height = $fontMetrics->get_font_height($font, $size);
          $width = $fontMetrics->get_text_width($text, $font, $size);

          $w = $pdf->get_width() - $width +80;
          $y = $pdf->get_height() - $text_height - 15;

          $pdf->page_text($w, $y, $text, $font, $size);






          $size = 9;

          // $text = "{{ $invoice->details_bottom3 }}";
          // $text ='auditors.lv';
          $text ='';

          // $str = 'Персонализиране';
          // $hexstr = '';
          // for ($i=0;$i<strlen($str);$i++) {
          // 	$hexstr .= sprintf('\\x%lx', ord($str[$i]));
          // }
          // // echo $str , '=' , $hexstr;
          // $text = $hexstr;

          $font = $fontMetrics->get_font("DejaVu Sans, dejavu sans light,sans-serif");
          $text_height = $fontMetrics->get_font_height($font, $size);
          $width = $fontMetrics->get_text_width($text, $font, $size);

          $w = 30;
          $y = $pdf->get_height() - $text_height - 15;

          $pdf->page_text($w, $y, $text, $font, $size);

      }










</script>
{{--  --}}
<div style="height: {{$settingsTopMargin}}px">

</div>
<table border="0" class="main-table" style="font-size: 12px;">
    <tr>
        <td width="100%">&nbsp;</td>
        <td min-width="60" class="text-right " nowrap>
            @if($invoice['invoicetype_id']==2)
                AVANSA
            @endif
            Rēķina nr.:
        </td>
        <td nowrap class="pr15">{{ $invoice->number}}</td>

    </tr>
    <tr>
        <td></td>
        <td class="text-right" nowrap><b>Datums:</b></td>
        <td nowrap class="pr15"><b>{{ $invoice->date}}</b></td>
    </tr>
    <tr>
        <td></td>
        <td class="text-right" nowrap><b>Samaksāt līdz: </b></td>
        <td nowrap class="pr15"><b>{{ $invoice->payment_date}}</b></td>
    </tr>
</table>
<table class="main-table" border="0">
    {{--<tr>--}}
    {{--<td colspan="10" align="right" width="100%" style="margin-left: auto;margin-right:0px;">--}}




    {{--</td>--}}

    {{--</tr>--}}

    <tr>
        <td colspan="10">
            <hr>
        </td>
    </tr>


    <tr>
        <td width="60">Izpildītājs:</td>
        <td width="200">{{$invoice->company->title}}</td>

        <td width="30"></td>

        <td width="60">Saņēmējs:</td>
        <td width="200">{{$invoice->partner->name}}</td>
    </tr>

    <tr>
        <td>Adrese:</td>
        <td>{{$invoice->company->address}}</td>

        <td></td>

        <td>Adrese:</td>
        <td>{{$invoice->partner->address}}</td>
    </tr>


    <tr>
        <td nowrap>Reģistr. Nr.:</td>
        <td>{{$invoice->company->registration_number}}</td>

        <td></td>

        <td nowrap>Reģistr. Nr.:</td>
        <td>{{$invoice->partner->registration_number}}</td>
    </tr>

    <tr>
        <td>PVN Nr.:</td>
        <td>{{$invoice->vat_number}}</td>

        <td></td>
        <td>PVN Nr.:</td>
        <td>{{$invoice->partner->vat_number}}</td>
    </tr>
    <tr>
        <td nowrap>Maks. saņēmējs:</td>
        <td><b>{{$invoice->payment_receiver}}</b></td>

        <td></td>
        <td>{{-- Maks. sanēmējs: --}}</td>
        <td></td>
    </tr>

    <tr>
        <td>Banka:</td>
        <td><b>{{$invoice->bank}}</b></td>

        <td></td>
        <td>Banka:</td>
        <td>{{ $invoice->partner->bank }}</td>
    </tr>

    <tr>
        <td>SWIFT:</td>
        <td><b>{{$invoice->swift}}</b></td>

        <td></td>
        <td>SWIFT:</td>
        <td>{{$invoice->partner->swift}}</td>
    </tr>

    <tr>
        <td>Konta nr:</td>
        <td><b>{{$invoice->account_number}}</b></td>

        <td></td>
        <td>Konta nr:</td>
        <td>{{$invoice->partner->account_number}}</td>
    </tr>
    <tr>
        <td colspan="10">
            <hr>
        </td>
    </tr>
    <tr>
        <td>Rēķina valūta:</td>
        <td>{{ $invoice->currency->name }}@if($invoice->currency->name !='EUR'), kurss ({{$invoice->currency->name}}/EUR): {{ $invoice->currency_rate}}
            @endif
        </td>
    </tr>
    <tr>
        <td>Detaļas:</td>
        <td colspan="10">{{ $invoice->details }}</td>
    </tr>
    @if($invoice->details1 )
        <tr>
            <td></td>
            <td colspan="10">{{ $invoice->details1 }}</td>
        </tr>
    @endif
    <tr>
        <td colspan="10">
            <hr>
        </td>
    </tr>


</table>

{{--<table>--}}
{{--<tr>--}}
{{--<td colspan="10">--}}
<table class="nested-table main-table" width="100%">
    <thead>
    <tr class="lines">
        @if($invoice->currency->name !='EUR')
            {{--<th width="260">Apraksts</th>--}}
            <th width="100%">Apraksts</th>
        @else
            {{--<th width="300">Apraksts</th>--}}
            <th width="100%">Apraksts</th>
        @endif
        <th>Vienība</th>
        <th>Daudzums</th>
        <th>Vienības cena</th>
        <th>Kopā, {{$invoice->currency->name}}</th>
        @if($invoice->currency->name !='EUR')
            <th>Kopā, EUR</th>
        @endif
        <th>PVN likme</th>
        <th class="only-left-border"></th>
    </tr>
    </thead>

    <tbody>
    @foreach($invoice->invoiceLines as $line)
        <tr>
            <td>{{ $line->title }}</td>
            <td class="text-center">{{ $line->unit->name }}</td>

            <?php $numberOfDigits = strlen(substr(strrchr($line->quantity, "."), 1));
            if ($numberOfDigits < 2) $numberOfDigits = 2;
            ?>
            <td class="text-right">{{ number_format($line->quantity,$numberOfDigits) }}</td>

            <?php $numberOfDigits = strlen(substr(strrchr($line->price, "."), 1));
            if ($numberOfDigits < 2) $numberOfDigits = 2;
            ?>
            <td class="text-right">{{ number_format($line->price, $numberOfDigits)  }}</td>
            <td class="text-right">{{ number_format($line->quantity * $line->price, 2) }}</td>

            @if($invoice->currency->name !='EUR')
                <td class="text-right">{{ number_format($line->quantity * $line->price / $invoice->currency_rate, 2) }}</td>
            @endif

            <td class="text-center">{{ $line->vat->name }}</td>
            <td class="only-left-border"></td>
        </tr>


        <?php
        $withoutVat[$line->vat->name] = isset($withoutVat[$line->vat->name]) ? $withoutVat[$line->vat->name] : 0;
        $withoutVat[$line->vat->name] += round($line->quantity * $line->price, 2);
        ?>
    @endforeach
    </tbody>
    @foreach($vats as $key=>$vat)
        @if(isset($withoutVat[$vat->name]) )

            <?php  $total_total = $total_total + $withoutVat[$vat->name] + round($withoutVat[$vat->name] * $vat->rate, 2); ?>

            <tr>
                <td colspan="10" class="no-border"></td>
            </tr>
            <tr>

                <td colspan="4" class="text-right no-border ">Bez PVN ({{ $vat->name }})</td>
                <td class="text-right">{{ number_format($withoutVat[$vat->name],2) }}</td>
                @if($invoice->currency->name !='EUR')
                    <td class="text-right">{{ number_format($withoutVat[$vat->name] / $invoice->currency_rate ,2) }}</td>
                @endif
                <td class="only-left-border"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right no-border ">PVN ({{ $vat->name }})</td>
                <td class="text-right">{{ number_format($withoutVat[$vat->name] * $vat->rate,2)}}</td>
                @if($invoice->currency->name !='EUR')
                    <td class="text-right">{{ number_format($withoutVat[$vat->name] * $vat->rate /  $invoice->currency_rate,2)}}</td>
                @endif
                <td class="only-left-border"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right no-border">Kopā ar PVN ({{ $vat->name }})</td>
                <td class="text-right">{{ number_format($withoutVat[$vat->name]+ $withoutVat[$vat->name] * $vat->rate,2)}}</td>

                @if($invoice->currency->name !='EUR')
                    <td nowrap class="text-right">{{ number_format( ($withoutVat[$vat->name]+ $withoutVat[$vat->name] * $vat->rate ) / $invoice->currency_rate,2)}}</td>
                @endif
                <td class="only-left-border"></td>
            </tr>

        @endif
    @endforeach




    <tr>
        <td colspan="10" class="no-border"></td>
    </tr>
    <tr>
        <td colspan="4" class="text-right no-border">Samaksai kopā ({{ $invoice->currency->name}}):</td>
        <td nowrap class="text-right">{{ number_format($total_total,2)}}</td>
        @if($invoice->currency->name !='EUR')
            <td nowrap class="text-right">{{ number_format($total_total / $invoice->currency_rate,2)}}</td>
        @endif
        <td class="only-left-border"></td>
    </tr>
    {{--</tbody>--}}
    {{--</table>--}}
    {{--</td>--}}
    {{--</tr>--}}

</table>
<table width="695px" border="0">
    <tr>
        <td class="text-left">
            <?php
            // require __DIR__ . '/vendor/autoload.php';
            // use \js\tools\numbers2words\Speller;
            $sumInWords = \js\tools\numbers2words\Speller::spellCurrency($total_total, 'lv', $invoice->currency->name);;
            ?>
            Summa vārdiem: {{ $sumInWords}}
        </td>
    </tr>

    <tr>
        <td>
            <hr>
        </td>
    </tr>
    <tr>
        <td class="text-left">
            {{ $invoice->details_bottom1}}
        </td>
    </tr>
    <tr>
        <td class="text-left">
            {{ $invoice->details_bottom2}}
        </td>
    </tr>
    <tr>
        <td>
            <table border="0" width="100%">
                <tr>
                    <td width="50%" class="text-left pt50 pb30">
                        Izpildītāja pārstāvis: <b>{{ $invoice->document_signer}}</b><br>
                        /{{ $invoice->date }}/
                    </td>
                    <td width="50%" class="text-right pt50 pb30 pr30">
                        @if($invoice->document_partner_signer)
                            Saņēmēja pārstāvis: <b>{{ $invoice->document_partner_signer}}</b><br>
                            /{{ $invoice->date }}/
                        @endif
                    </td>
                </tr>
            </table>
        </td>

    </tr>
    {{-- <tr><td colspan="10"><hr></td></tr> --}}
    <tr>
        <td class="text-center">
            {{ $invoice->details_bottom3}}
        </td>
    </tr>

</table>


<div class="footer">
    {{-- Page <span class="pagenum"></span> --}}
    {{-- {{ $invoice->details_bottom3}}  --}}
</div>

