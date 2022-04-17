<style>
    * {
        font-family: DejaVu Sans, sans-serif;
        font-size: 8px;
    }


    .lines * {
        font-size: 8px;
    }


    table {
        border-collapse: collapse;
    }

    tbody td {
        vertical-align: top;
    }

    .lines td, .lines th {
        border: 1px solid #3e3e3e;
    }

    .lines th {
        background-color: #3e3e3e;
        color: white;
    }

    .border-right-white {
        border-right: 1px solid white !important;
    }

    @page {
        size: 7in 9.25in;
        margin: 17mm 16mm 17mm 16mm;
    }

    body {
        max-width: 19cm;
    }

</style>


<table border="0" style="width: 100%;">
    <tr>
        <td style="width: 50%">
            <table class="lines">
                <thead>
                <tr>
                    <th colspan="5">Kopsavilkums</th>
                </tr>
                <tr>
                    <th>Budžets</th>
                    <th>Konts</th>
                    <th>Bez PVN</th>
                    <th>PVN</th>
                    <th>Kopā</th>
                </tr>
                </thead>
                @foreach($data->summary['details'] as $detail)
                    <tr>
                        <td>{{$detail['budget']}}</td>
                        <td>{{$detail['account_code']}}</td>
                        <td style="text-align: right">{{number_format($detail['amount_without_vat'], 2)}}</td>
                        <td style="text-align: right">{{number_format($detail['vat'], 2)}}</td>
                        <td style="text-align: right">{{number_format($detail['amount_with_vat'], 2)}}</td>
                    </tr>
                @endforeach
            </table>
        </td>
        <td style="width: 50%">
            <table border="0" width="100%" style="margin-left: 10px;">
                <tr>
                    <td style="width: 110px;">Numurs:</td>
                    <td colspan="2"><strong>{{$data->summary['no'] ?? '-'}}</strong></td>
                </tr>
                <tr>
                    <td>Datums:</td>
                    <td colspan="2"><strong>{{$data->summary['date'] ?? '-'}}</strong></td>
                </tr>
                <tr>
                    <td>Avansu norēķinu persona:</td>
                    <td colspan="2"><strong>{{$data->summary['employee_name'] ?? '-'}}  </strong>
                    </td>

                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td style=""><strong>Sākuma atlikums, EUR</strong></td>
                    <td style="width: 50px; text-align: right;"><strong>0.00</strong></td>
                    <td></td>
                </tr>

                <tr>
                    <td><strong>Saņemts kopā, EUR</strong></td>
                    <td style="text-align: right"><strong>0.00</strong></td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong>Izmaksas kopā, EUR</strong></td>
                    <td style="text-align: right"><strong>{{number_format($data->summary['total_with_vat'], 2)}}</strong></td>
                    <td></td>
                </tr>

                <tr>
                    <td><strong>Beigu atlikums, EUR</strong></td>
                    <td style="text-align: right"><strong>{{number_format($data->summary['total_with_vat'], 2)}}</strong></td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>

</table>

<br>


<table class="lines" style="width: 100%">
    <thead>
    <tr>
        <th class="border-right-white" style="width: 20px">Nr.</th>
        <th class="border-right-white" style="width: 55px">Datums</th>
        <th class="border-right-white" style="width: 150px">Partneris</th>
        <th class="border-right-white">Apraksts</th>
        <th class="border-right-white" style="width: 30px;">Budž.</th>
        <th class="border-right-white" style="width: 30px;">Konts</th>
        <th class="border-right-white" style="width: 40px;">Bez PVN</th>
        <th class="border-right-white" style="width: 40px;">PVN</th>
        <th style="width: 40px;">Kopā</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data->lines ?? [] as $line)
        <tr>
            <td>
                {{$line->line_no}}
            </td>
            <td>
                {{$line->line_date}}
            </td>
            <td>
                {{\App\Services\Helper::cutString(strval($line->line_partner_name), 30)}}<br>
                {{$line->line_partner_vat_number}}
            </td>
            <td>
                {{\App\Services\Helper::cutString(strval($line->line_description), 40)}}
            </td>
            <td>
                {{$line->line_budget_code}}
            </td>
            <td>
                {{$line->line_account_code}}
            </td>


            <td style="text-align: right">
                {{number_format($line->line_amount_without_vat, 2)}}
            </td>

            <td style="text-align: right">
                {{number_format($line->line_amount_vat, 2)}}
            </td>

            <td style="text-align: right">
                {{number_format($line->line_amount_with_vat, 2)}}
            </td>

        </tr>
    @endforeach
    <tr>
        <td colspan="6" style="text-align: right">Kopā:</td>
        <td style="text-align: right">{{number_format($data->summary['total_without_vat'], 2)}}</td>
        <td style="text-align: right">{{number_format($data->summary['total_vat'], 2)}}</td>
        <td style="text-align: right">{{number_format($data->summary['total_with_vat'], 2)}}</td>
    </tr>
    </tbody>
</table>