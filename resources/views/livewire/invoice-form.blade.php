<div>
    <style>
        .invoice-form-card {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.03);
        }
        .invoice-section-card {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 1.25rem;
            height: 100%;
        }
        .invoice-section-title {
            font-size: 0.875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #475569;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .invoice-lines-table {
            margin-bottom: 0;
        }
        .invoice-lines-table thead th {
            background-color: #f1f5f9 !important;
            color: #334155 !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            padding: 9px 8px !important;
            border-bottom: 2px solid #cbd5e1 !important;
        }
        .invoice-lines-table td {
            padding: 5px 4px !important;
            vertical-align: middle !important;
            background-color: #ffffff;
            border-color: #e2e8f0 !important;
        }
        .invoice-lines-table tr:hover td {
            background-color: #f8fafc;
        }
        .invoice-lines-table input.form-control,
        .invoice-lines-table select.form-select,
        .invoice-lines-table select.form-control,
        .invoice-lines-table textarea.form-control {
            height: 34px !important;
            min-height: 34px !important;
            max-height: 34px !important;
            padding: 4px 8px !important;
            font-size: 0.8125rem !important;
            line-height: 1.4 !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 6px !important;
            box-sizing: border-box !important;
            background-color: #ffffff !important;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .invoice-lines-table input.form-control:focus,
        .invoice-lines-table select.form-select:focus,
        .invoice-lines-table textarea.form-control:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15) !important;
            outline: 0;
        }
        .invoice-lines-table textarea.form-control {
            resize: none !important;
            overflow: hidden !important;
        }
        .invoice-lines-table .remove-line {
            height: 34px !important;
            width: 34px !important;
            padding: 0 !important;
            border-radius: 6px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .summary-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .summary-header {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
            font-weight: 700;
            font-size: 0.875rem;
            color: #1e293b;
        }
        .summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .summary-row.total-row {
            background: #f8fafc;
            font-weight: 700;
            font-size: 0.9rem;
            color: #0f172a;
            border-top: 1px solid #cbd5e1;
        }
        .summary-row.payable-row {
            background: #eff6ff;
            color: #1d4ed8;
            font-weight: 800;
            font-size: 1.05rem;
            border-top: 2px solid #3b82f6;
            padding: 0.85rem 1rem;
        }
        .form-label-neat {
            font-size: 0.775rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.25rem;
            display: block;
        }
        .form-label-neat.required::after {
            content: " *";
            color: #dc2626;
            font-weight: bold;
        }
        .form-control-neat,
        .form-select-neat {
            height: 36px;
            font-size: 0.875rem;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            padding: 0.375rem 0.65rem;
        }
        .form-control-neat:focus,
        .form-select-neat:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
        }
        .invoice-actions-footer {
            border-top: 1px solid #e2e8f0;
            padding-top: 1.25rem;
            margin-top: 1.5rem;
        }
    </style>

    <div class="card invoice-form-card">
        <!-- Header -->
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                    <i class="fa-solid fa-file-invoice-dollar fs-4"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-slate-800">
                        @if($invoiceId) {{ __('Labot rēķinu') }} @else {{ __('Jauns rēķins') }} @endif
                    </h4>
                    <span class="small text-muted">{{ __('Aizpildiet rēķina rekvizītus, preču rindas un norēķinu datus') }}</span>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button"
                        class="btn btn-outline-secondary btn-sm px-3 rounded-3"
                        wire:click="closeInvoiceForm">
                    <i class="fa-solid fa-arrow-left me-1"></i> {{ __('Atpakaļ uz sarakstu') }}
                </button>
                <button type="button"
                        class="btn btn-primary btn-sm px-3 rounded-3 fw-semibold"
                        onclick="saveInvoiceForm(false)">
                    <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('Saglabāt') }}
                </button>
                <button type="button"
                        class="btn btn-success btn-sm px-3 rounded-3 fw-semibold"
                        onclick="saveInvoiceForm(true)">
                    <i class="fa-solid fa-check me-1"></i> {{ __('Saglabāt un iziet') }}
                </button>
            </div>
        </div>

        <div class="card-body p-4">
            <form class="form-horizontal form1"
                  id="invoiceForm"
                  wire:submit.prevent="saveInvoice(Object.fromEntries(new FormData($event.target)), true)">

                <!-- Row 1: Document Details & Financial Parameters -->
                <div class="row g-4 mb-4">
                    <!-- Left Column: Document Metadata -->
                    <div class="col-lg-6">
                        <div class="invoice-section-card">
                            <div class="invoice-section-title">
                                <i class="fa-solid fa-file-lines text-primary-500"></i>
                                <span>Dokumenta rekvizīti</span>
                            </div>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label for="number" class="form-label-neat required">Rēķina Nr.</label>
                                    {!! Form::text('number', isset($invoice) ? $invoice['number'] : null , ['class'=>'form-control form-control-neat fw-bold font-monospace', 'placeholder'=>'Piem., 2026-001'] ) !!}
                                </div>

                                <div class="col-sm-6">
                                    <label for="invoicetype_id" class="form-label-neat">Rēķina veids</label>
                                    {!! Form::select('invoicetype_id', isset($invoicetypes) ? $invoicetypes->pluck('title', 'id') : [] , isset($invoice) ? $invoice['invoicetype_id'] : null , ['class'=>'form-select form-select-neat', 'id'=>'invoicetype_id'] ) !!}
                                </div>

                                <div class="col-sm-6">
                                    <label for="date" class="form-label-neat required">Izrakstīšanas datums</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="date"
                                               value="{{isset($invoice) ? $invoice['date'] : \Carbon\Carbon::now()->format('d.m.Y') }}"
                                               class="form-control form-control-neat" placeholder="Datums" id="dp1" readonly>
                                        <span class="input-group-text bg-light text-muted"><i class="fa-regular fa-calendar"></i></span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label for="payment_date" class="form-label-neat required">Apmaksas termiņš</label>
                                    <div class="input-group input-group-sm">
                                        {!! Form::text('payment_date', isset($invoice) ? $invoice['payment_date'] : \Carbon\Carbon::now()->format('d.m.Y')  , ['class'=>'form-control form-control-neat', 'placeholder'=>'Apmaksas datums', 'id'=>'dp2', 'readonly'] ) !!}
                                        <span class="input-group-text bg-light text-muted"><i class="fa-regular fa-calendar"></i></span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label for="structuralunit_id" class="form-label-neat">Struktūrvienība</label>
                                    {!! Form::select('structuralunit_id', isset($structuralunits) ? $structuralunits->pluck('title', 'id') : [] , isset($invoice) ? $invoice['structuralunit_id'] : null , ['class'=>'form-select form-select-neat'] ) !!}
                                </div>

                                <div class="col-sm-6">
                                    <label for="vat_number" class="form-label-neat">Uzņēmuma PVN numurs</label>
                                    {!! Form::select('vat_number',isset($companyVatNumbers) ? $companyVatNumbers->pluck('vat_number', 'vat_number') : [] ,isset($invoice) ? $invoice['vat_number'] :  ($companyVatNumbers[0]->vat_number ?? null) , ['class'=>'form-select form-select-neat', 'placeholder'=>'- Izvēlēties PVN nr. -'] ) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Partner & Currency -->
                    <div class="col-lg-6">
                        <div class="invoice-section-card">
                            <div class="invoice-section-title">
                                <i class="fa-solid fa-handshake text-primary-500"></i>
                                <span>Darījuma partneris & Parametri</span>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="partner_id" class="form-label-neat required">Darījuma partneris</label>
                                    <livewire:partner-select name="partner_id"
                                                             :selectedPartnerId="$invoice['partner_id']??null"/>
                                </div>

                                <div class="col-sm-6">
                                    <label for="currency_id" class="form-label-neat">Valūta</label>
                                    {!! Form::select('currency_id', $currencies ,isset($invoice) ? $invoice['currency_id'] : null , ['class'=>'form-select form-select-neat', 'id'=>'currency_id'] ) !!}
                                </div>

                                <div class="col-sm-6">
                                    <label for="currency_rate" class="form-label-neat">Valūtas kurss (pret 1 EUR)</label>
                                    {!! Form::text('currency_rate', isset($invoice) ? $invoice['currency_rate'] : 1 , ['class'=>'form-control form-control-neat font-monospace', 'placeholder'=>'1.000', 'id'=>'currency_rate'] ) !!}
                                </div>

                                <div class="col-12">
                                    <label for="bank_id" class="form-label-neat">Papildu maksājumu saņēmējs (bankas konts)</label>
                                    {!! Form::select('bank_id', $bank ,isset($selectedBank) ? $selectedBank['id'] : null , ['class'=>'form-select form-select-neat', 'placeholder'=>'- Izvēlēties maksājuma saņēmēju -'] ) !!}
                                </div>

                                <div class="col-12">
                                    <label for="details_self" class="form-label-neat">Iekšējais komentārs (redzams tikai sistēmā)</label>
                                    {!! Form::text('details_self', isset($invoice) ? $invoice['details_self'] : null , ['class'=>'form-control form-control-neat', 'placeholder'=>'Piezīmes tikai iekšējai lietošanai...'] ) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PPR Goods Delivery Section (Conditional) -->
                <div id="ppr_fields" class="@if(($invoice['invoicetype_id'] ?? 'x')  != 3) d-none @endif mb-4">
                    <div class="p-3 rounded-3 bg-success-50 border border-success-subtle">
                        <div class="d-flex align-items-center gap-2 mb-2 text-success-emphasis fw-bold small text-uppercase">
                            <i class="fa-solid fa-truck-fast"></i> Preču pavadzīmes piegādes rekvizīti
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="goods_address_from" class="form-label-neat text-success-emphasis">Preču izsniegšanas vieta</label>
                                {!! Form::text('goods_address_from', isset($invoice) ? $invoice['goods_address_from'] : null , ['class'=>'form-control form-control-neat', 'placeholder'=>'Izsniegšanas adrese'] ) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="goods_address_to" class="form-label-neat text-success-emphasis">Preču saņemšanas vieta</label>
                                {!! Form::text('goods_address_to', isset($invoice) ? $invoice['goods_address_to'] : null , ['class'=>'form-control form-control-neat', 'placeholder'=>'Saņemšanas adrese'] ) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="goods_deliverer" class="form-label-neat text-success-emphasis">Pārvadātājs</label>
                                {!! Form::text('goods_deliverer', isset($invoice) ? $invoice['goods_deliverer'] : null , ['class'=>'form-control form-control-neat', 'placeholder'=>'Organizācija, Auto Nr., šoferis'] ) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoice General Details -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="details" class="form-label-neat">Rēķina pamata apraksts</label>
                        {!! Form::text('details', isset($invoice) ? $invoice['details'] : null , ['class'=>'form-control form-control-neat', 'placeholder'=>'Piem., Saskaņā ar līgumu Nr. 12/2026...'] ) !!}
                    </div>
                    <div class="col-md-6">
                        <label for="details1" class="form-label-neat">Papildu apraksts / Piezīmes</label>
                        {!! Form::text('details1', isset($invoice) ? $invoice['details1'] : null , ['class'=>'form-control form-control-neat', 'placeholder'=>'Papildu norādes rēķina saņēmējam...'] ) !!}
                    </div>
                </div>

                <!-- Invoice Lines Section -->
                <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold text-slate-800 mb-0 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-list-check text-primary-500"></i> Preču un pakalpojumu rindas
                        </h6>
                    </div>

                    <div class="table-responsive border rounded-3 overflow-hidden shadow-xs">
                        <table class="table table-modern align-middle mb-0 invoice-lines-table">
                            <thead>
                            <tr>
                                <th style="width: 100px;">Kods</th>
                                <th>Prece / Pakalpojums</th>
                                <th style="width: 110px;">Mērv.</th>
                                <th style="width: 110px;" class="text-end">Daudzums</th>
                                <th style="width: 120px;" class="text-end">Cena</th>
                                <th style="width: 120px;" class="currencyData text-end">
                                    <div id="invoice_curency_name"></div>
                                </th>
                                <th style="width: 120px;" class="text-end">Kopā EUR</th>
                                <th style="width: 100px;">PVN</th>
                                <th style="width: 45px;" class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>

                            @if( isset($invoice) )
                                @foreach($invoiceLines as $index => $line)
                                    <tr>
                                        <td>
                                            {!! Form::text('code['.$index.']', isset($line) ? $line['code'] : null , ['style'=>'min-width:50px','class'=>'form-control form-control-sm line_code line-1 text-end font-monospace', 'placeholder'=>'Kods'] ) !!}
                                        </td>
                                        <td>
                                            @if($line->id ?? null)
                                                {!! Form::hidden('line_id['.$index.']', $line->id) !!}
                                            @endif
                                            {!! Form::textarea('title['.$index.']', isset($line) ? $line['title'] : null , ['size'=>'100%xAuto', 'style'=>'height: 34px; min-width:200px','class'=>'form-control form-control-sm line_title line-1', 'placeholder'=>'Preces vai pakalpojuma nosaukums', 'rows'=>1] ) !!}
                                        </td>
                                        <td>
                                            {!! Form::select('unit_id['.$index.']', $units->pluck('name','id'), isset($line) ? $line['unit_id'] : null , ['style'=>'min-width:80px','class'=>'form-select form-select-sm line_unit line-1 text-end'] ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('quantity['.$index.']', isset($line) ? $line['quantity'] : null , ['style'=>'min-width:80px','class'=>'form-control form-control-sm line_quantity line-1 text-end font-monospace', 'placeholder'=>'0'] ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('price['.$index.']', isset($line) ? $line['price'] : null , ['style'=>'min-width:80px','class'=>'form-control form-control-sm line_price line-1 text-end font-monospace', 'placeholder'=>'0.00'] ) !!}
                                        </td>
                                        <td class="currencyData">
                                            {!! Form::text('total['.$index.']',  isset($line) ? ROUND($line['price'] * $line['quantity'], 2)  : null , ['style'=>'min-width:80px', 'class'=>'form-control form-control-sm line_total line-1 text-end font-monospace', 'placeholder'=>'0.00', 'readonly'] ) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('total_base_currency['.$index.']',  isset($line) ? ROUND($line['price'] * $line['quantity'] * $invoice->currency_rate, 2)  : null , ['style'=>'min-width:80px', 'class'=>'form-control form-control-sm line_total_base_currency line-1 text-end font-monospace', 'placeholder'=>'0.00', 'readonly'] ) !!}
                                        </td>
                                        <td>
                                            {!! Form::select('vat_id['.$index.']', $vats->pluck('name', 'id') ,isset($line) ? $line['vat_id'] : null , ['style'=>'min-width:70px', 'class'=>'form-select form-select-sm line_vat_id line-1'] ) !!}
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-line" title="Dzēst rindu">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            {{-- Empty line template --}}
                            <tr id="line-empty-div" class="d-none">
                                <td>
                                    {!! Form::text('code[]', null , ['style'=>'min-width:50px','class'=>'form-control form-control-sm line_code line-1 text-end font-monospace', 'placeholder'=>'Kods'] ) !!}
                                </td>
                                <td>
                                    {!! Form::hidden('line_id[]', null) !!}
                                    {!! Form::textarea('title[]', null , ['size'=>'100%xAuto', 'style'=>'height: 34px', 'class'=>'form-control form-control-sm line_title line-1', 'placeholder'=>'Preces vai pakalpojuma nosaukums', 'rows'=>1] ) !!}
                                </td>
                                <td>
                                    {!! Form::select('unit_id[]', $units->pluck('name', 'id') , $units[0]->id ?? null , ['class'=>'form-select form-select-sm line_unit line-1 text-end'] ) !!}
                                </td>
                                <td>
                                    {!! Form::text('quantity[]', null , ['class'=>'form-control form-control-sm line_quantity line-1 text-end font-monospace', 'placeholder'=>'0'] ) !!}
                                </td>
                                <td>
                                    {!! Form::text('price[]', null , ['class'=>'form-control form-control-sm line_price line-1 text-end font-monospace', 'placeholder'=>'0.00'] ) !!}
                                </td>
                                <td class="currencyData">
                                    {!! Form::text('total[]', null , ['class'=>'form-control form-control-sm line_total line-1 text-end font-monospace', 'placeholder'=>'0.00', 'readonly'] ) !!}
                                </td>
                                <td>
                                    {!! Form::text('total_base_currency[]', null , ['class'=>'form-control form-control-sm line_total_base_currency line-1 text-end font-monospace', 'placeholder'=>'0.00', 'readonly'] ) !!}
                                </td>
                                <td>
                                    {!! Form::select('vat_id[]', $vats->pluck('name', 'id') , ($vats[0]->id ?? null) , ['class'=>'form-select form-select-sm line_vat_id line-1'] ) !!}
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-line" title="Dzēst rindu">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr id="placeNewRow"></tr>

                            {{-- Tax totals by rate --}}
                            @foreach($vats as $vat)
                                <tr class="d-none">
                                    <td colspan="5" class="text-end fw-semibold">
                                        {{ 'Kopā bez PVN ('.$vat->name.'):' }}
                                    </td>
                                    <td class="currencyData">
                                        {!! Form::text('invoiceBeforeTaxTotal_'.$vat->id, null , ['class'=>'form-control form-control-sm text-end font-monospace', 'placeholder'=>'', 'id'=>'invoiceBeforeTaxTotal_'.$vat->id, 'readonly'] ) !!}
                                    </td>
                                    <td>
                                        {!! Form::text('invoiceBeforeTaxTotal_base_currency_'.$vat->id, null , ['class'=>'form-control form-control-sm text-end font-monospace', 'placeholder'=>'', 'id'=>'invoiceBeforeTaxTotal_base_currency_'.$vat->id, 'readonly'] ) !!}
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end text-muted small">
                                        {{ 'PVN ('.$vat->name.'):' }}
                                    </td>
                                    <td class="currencyData">
                                        {!! Form::text('invoiceVat_'.$vat->id, null , ['class'=>'form-control form-control-sm text-end font-monospace', 'placeholder'=>'', 'id'=>'invoiceVat_'.$vat->id, 'readonly'] ) !!}
                                    </td>
                                    <td>
                                        {!! Form::text('invoiceVat_base_currency_'.$vat->id, null , ['class'=>'form-control form-control-sm text-end font-monospace', 'placeholder'=>'', 'id'=>'invoiceVat_base_currency_'.$vat->id, 'readonly'] ) !!}
                                    </td>
                                    <td colspan="2"></td>
                                </tr>

                                <tr>
                                    <td colspan="5" class="text-end text-muted small">
                                        {{ 'Kopā ar PVN ('.$vat->name.'):' }}
                                    </td>
                                    <td class="currencyData">
                                        {!! Form::text('invoiceTotal_'.$vat->id, null , ['class'=>'form-control form-control-sm text-end font-monospace', 'placeholder'=>'', 'id'=>'invoiceTotal_'.$vat->id, 'readonly'] ) !!}
                                    </td>
                                    <td>
                                        {!! Form::text('invoiceTotal_base_currency_'.$vat->id, null , ['class'=>'form-control form-control-sm text-end font-monospace', 'placeholder'=>'', 'id'=>'invoiceTotal_base_currency_'.$vat->id, 'readonly'] ) !!}
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            @endforeach

                            <tr class="table-light">
                                <td colspan="5" class="text-end fw-bold">
                                    {{ 'Kopā:' }}
                                </td>
                                <td class="currencyData">
                                    {!! Form::text('invoiceTotal', null , ['class'=>'form-control form-control-sm text-end fw-bold font-monospace', 'placeholder'=>'', 'id'=>'invoiceTotal', 'readonly'] ) !!}
                                </td>
                                <td>
                                    {!! Form::text('invoiceTotal_base_currency', null , ['class'=>'form-control form-control-sm text-end fw-bold font-monospace', 'placeholder'=>'', 'id'=>'invoiceTotal_base_currency', 'readonly'] ) !!}
                                </td>
                                <td colspan="2"></td>
                            </tr>

                            {{-- Advance payment --}}
                            <tr>
                                <td colspan="5" class="text-end text-muted small">
                                    {{ 'Saņemtais avanss:' }}
                                </td>
                                <td class="currencyData">
                                    {!! Form::text('invoiceAdvancePayment', null , ['class'=>'form-control form-control-sm text-end font-monospace text-danger', 'placeholder'=>'', 'id'=>'invoiceAdvancePayment', 'readonly'] ) !!}
                                </td>
                                <td>
                                    {!! Form::text('invoiceAdvancePayment_base_currency', null , ['class'=>'form-control form-control-sm text-end font-monospace text-danger', 'placeholder'=>'', 'id'=>'invoiceAdvancePayment_base_currency', 'readonly'] ) !!}
                                </td>
                                <td colspan="2"></td>
                            </tr>

                            {{-- Payable --}}
                            <tr class="table-primary">
                                <td colspan="5" class="text-end fw-bold text-primary-800 fs-6">
                                    {{ 'Apmaksai:' }}
                                </td>
                                <td class="currencyData">
                                    {!! Form::text('invoicePayable', null , ['class'=>'form-control form-control-sm text-end fw-bold font-monospace fs-6 text-primary-800', 'placeholder'=>'', 'id'=>'invoicePayable', 'readonly'] ) !!}
                                </td>
                                <td>
                                    {!! Form::text('invoicePayable_base_currency', null , ['class'=>'form-control form-control-sm text-end fw-bold font-monospace fs-6 text-primary-800', 'placeholder'=>'', 'id'=>'invoicePayable_base_currency', 'readonly'] ) !!}
                                </td>
                                <td colspan="2"></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="mt-2">
                        <button type="button" class="btn btn-outline-primary btn-sm px-3 rounded-pill" id="addLine">
                            <i class="fa-solid fa-plus me-1"></i> Pievienot rindu
                        </button>
                    </div>
                </div>

                <!-- Prepayments Section -->
                <div class="card bg-slate-50 border border-slate-200 rounded-3 p-3 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2 fw-bold text-slate-700 small text-uppercase">
                            <i class="fa-solid fa-money-bill-transfer text-primary-600"></i>
                            <span>Saņemtie avansa maksājumi</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 rounded-pill" id="addRepaymentLine">
                            <i class="fa-solid fa-plus me-1"></i> Pievienot avansu
                        </button>
                    </div>

                    <div id="place_for_prepayments">
                        @foreach($invoiceAdvancePayments as $index => $pre)
                            <div class="row g-2 mb-2 default_advance_payment_form_ align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label-neat">Piezīmes par avansu</label>
                                    <input type="text"
                                           value="{{$pre->details ?? null}}"
                                           name="prePaymentDetails[{{$index}}]"
                                           class="form-control form-control-neat text-start"
                                           placeholder="Maksājuma uzdevuma Nr., datums...">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-neat">Avansa datums</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text"
                                               name="prePaymentDate[{{$index}}]"
                                               value="{{$pre->date ?? \Carbon\Carbon::now()->format('d.m.Y')}}"
                                               class="form-control form-control-neat date" placeholder="Datums" readonly>
                                        <span class="input-group-text bg-white text-muted"><i class="fa-regular fa-calendar"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-neat">Summa (EUR)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text"
                                               value="{{$pre->amount ?? 0}}"
                                               name="prePaymentAmount[{{$index}}]"
                                               class="form-control form-control-neat amount text-end font-monospace"
                                               placeholder="0.00">
                                        <button type="button" class="btn btn-outline-danger remove-line" title="Dzēst avansu">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Signers & Legal Footers Section -->
                <div class="border-top pt-4 mt-2 mb-3">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label for="document_signer" class="form-label-neat required">Dokumentu sagatavoja / izsniedza</label>
                            {!! Form::text('document_signer', isset($invoice) ? $invoice['document_signer'] : null , ['class'=>'form-control form-control-neat', 'placeholder'=>'Vārds, Uzvārds, Amats'] ) !!}
                            <span class="small text-muted" style="font-size: 0.75rem;">Norādiet personu, kura sagatavojusi rēķinu</span>
                        </div>

                        <div class="col-md-6">
                            <label for="document_partner_signer" class="form-label-neat">Partnera pārstāvis / saņēmējs</label>
                            {!! Form::text('document_partner_signer', isset($invoice) ? $invoice['document_partner_signer'] : null , ['class'=>'form-control form-control-neat', 'placeholder'=>'Partnera vārds, uzvārds vai amats'] ) !!}
                            <span class="small text-muted" style="font-size: 0.75rem;">Pēc izvēles — persona, kas pieņem preces/pakalpojumus</span>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="details_bottom1" class="form-label-neat">Papildu piezīmes 1 (lapas kreisajā apakšā)</label>
                            {!! Form::text('details_bottom1', isset($invoice) ? $invoice['details_bottom1'] : null , ['class'=>'form-control form-control-neat', 'placeholder'=>'Piem., Rekvizīti apmaksai...'] ) !!}
                        </div>
                        <div class="col-md-6">
                            <label for="details_bottom2" class="form-label-neat">Papildu piezīmes 2 (lapas labajā apakšā)</label>
                            {!! Form::text('details_bottom2', isset($invoice) ? $invoice['details_bottom2'] : null , ['class'=>'form-control form-control-neat', 'placeholder'=>'Piem., Apmaksa 14 dienu laikā...'] ) !!}
                        </div>
                        <div class="col-12">
                            <label for="details_bottom3" class="form-label-neat">Papildu piezīmes 3 (centrētas pašā apakšā)</label>
                            {!! Form::text('details_bottom3', isset($invoice) ? $invoice['details_bottom3'] : null , ['class'=>'form-control form-control-neat text-center', 'placeholder'=>'Piem., Rēķins ir sagatavots elektroniski un ir derīgs bez paraksta'] ) !!}
                        </div>
                    </div>
                </div>

                <!-- Bottom Action Toolbar -->
                <div class="invoice-actions-footer d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2 text-muted small">
                        <i class="fa-solid fa-shield-check text-success"></i>
                        <span>Visi aprēķini un nodokļi tiek sinhronizēti automātiski</span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button type="button"
                                class="btn btn-outline-secondary px-3 py-2 rounded-3"
                                wire:click="closeInvoiceForm">
                            <i class="fa-solid fa-xmark me-1"></i> {{ __('Iziet nesaglabājot') }}
                        </button>
                        <button type="button"
                                class="btn btn-primary px-3 py-2 rounded-3 fw-semibold shadow-xs"
                                onclick="saveInvoiceForm(false)">
                            <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('Saglabāt') }}
                        </button>
                        <button type="button"
                                class="btn btn-success px-4 py-2 rounded-3 fw-semibold shadow-xs"
                                onclick="saveInvoiceForm(true)">
                            <i class="fa-solid fa-check me-1"></i> {{ __('Saglabāt un atgriezties') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        window.saveInvoiceForm = function(returnToList) {
            const form = document.getElementById('invoiceForm');
            if (form) {
                const data = Object.fromEntries(new FormData(form));
                @this.saveInvoice(data, returnToList);
            }
        };

        $(document).ready(function () {
            function round(number) {
                return Math.round((number + Number.EPSILON) * 100) / 100;
            }

            function initDatepicker(selector) {
                $(selector).datepicker({
                    format: 'dd.mm.yyyy',
                    weekStart: 1,
                    todayBtn: "linked",
                    todayHighlight: true,
                    autoclose: true,
                    daysOfWeekDisabled: [],
                    daysOfWeekHighlighted: [0, 6],
                });
            }

            // ------------------------------------------------------------------------clalculateEachLine
            function recalculateInvoiceData() {
                var invoiceTotatWithOutVatForSpecificVatRateCurrentCurrency = {
                    '1': 0, '2': 0, '3': 0, '4': 0, '5': 0, '6': 0, '7': 0, '8': 0, '9': 0, '10': 0, '11': 0, '12': 0, '13': 0
                };
                var invoiceTotatWithOutVatForSpecificVatRateBaseCurrency = {
                    '1': 0, '2': 0, '3': 0, '4': 0, '5': 0, '6': 0, '7': 0, '8': 0, '9': 0, '10': 0, '11': 0, '12': 0, '13': 0
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

                var vats = <?php echo json_encode($vats); ?>;

                for (var key in vats) {
                    var currencyRate = $('#currency_rate').val();
                    let beforeTax = invoiceTotatWithOutVatForSpecificVatRateCurrentCurrency[vats[key].id];
                    let tax = round((beforeTax * vats[key].rate)).toFixed(2);
                    let taxBaseCurrency = round(tax / currencyRate).toFixed(2);

                    let total = parseFloat(beforeTax) + parseFloat(tax);
                    let totalCurrency = round(parseFloat(total) / currencyRate).toFixed(2);

                    let beforeTaxBaseCurrency = totalCurrency - taxBaseCurrency;

                    invoiceTotalCurency += parseFloat(total);
                    invoiceTotalBaseCurency += parseFloat(totalCurrency);

                    let beforeTaxAccounting = accounting.formatMoney(beforeTax);
                    let taxAccounting = accounting.formatMoney(tax);
                    let totalAccounting = accounting.formatMoney(total);

                    let beforeTaxAccounting_baseCurrency = accounting.formatMoney(beforeTaxBaseCurrency);
                    let taxAccounting_baseCurrency = accounting.formatMoney(taxBaseCurrency);
                    let totalAccounting_baseCurrency = accounting.formatMoney(totalCurrency);

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

            var prepaymentLineIndex = 100;
            const prepaymentLine = `
<div class="row g-2 mb-2 default_advance_payment_form align-items-end">
    <div class="col-md-5">
        <label class="form-label-neat">Piezīmes par avansu</label>
        <input type="text"
               value=""
               name="prePaymentDetails[]"
               class="form-control form-control-neat text-start"
               placeholder="Maksājuma uzdevuma Nr., datums...">
    </div>
    <div class="col-md-3">
        <label class="form-label-neat">Avansa datums</label>
        <div class="input-group input-group-sm">
            <input type="text"
                   name="prePaymentDate[]"
                   value=""
                   class="form-control form-control-neat date" placeholder="Datums" readonly>
            <span class="input-group-text bg-white text-muted"><i class="fa-regular fa-calendar"></i></span>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label-neat">Summa (EUR)</label>
        <div class="input-group input-group-sm">
            <input type="text"
                   value=""
                   name="prePaymentAmount[]"
                   class="form-control form-control-neat amount text-end font-monospace" placeholder="0.00">
            <button type="button" class="btn btn-outline-danger remove-line" title="Dzēst avansu">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>
    </div>
</div>`;

            initDatepicker('.date');

            if (document.querySelector('#addRepaymentLine') && document.querySelector('#addRepaymentLine').getAttribute('listener') !== 'true') {
                document.querySelector('#addRepaymentLine').addEventListener('click', function () {
                    addPrepaymentLine();
                });
            }

            const addPrepaymentLine = function (date, amount) {
                if (!date || date == '' || date == 'undefined') {
                    date = "{{date('d.m.Y')}}";
                }
                if (!amount || amount == '' || amount == 'undefined') {
                    amount = "0.00";
                }

                amount = Number.parseFloat(amount).toFixed(2);
                let id = uid();
                let newLine = document.createElement('div');
                newLine.id = '_' + id;
                newLine.innerHTML = prepaymentLine;

                newLine.querySelectorAll('input').forEach(function (el) {
                    let name = el.getAttribute('name');
                    name = name.replace('[]', '[' + prepaymentLineIndex + ']');
                    el.setAttribute('name', name);
                });

                prepaymentLineIndex++;
                newLine.querySelector('.date').value = date;
                newLine.querySelector('.date').id = id;
                newLine.querySelector('.amount').value = amount;
                newLine.querySelector('.amount').addEventListener('change', function () {
                    recalculateInvoiceData();
                });
                document.querySelector('#place_for_prepayments').append(newLine);
                initDatepicker('#' + id);
            }

            accounting.settings = {
                currency: {
                    symbol: "",
                    format: "%s%v",
                    decimal: ".",
                    thousand: ",",
                    precision: 2
                },
                number: {
                    precision: 0,
                    thousand: ",",
                    decimal: "."
                }
            };

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
                setCurrencyRateForBaseCurrency();
            });

            var addlineIndex = 100;

            $('#addLine').on('click', function () {
                let div = $('#line-empty-div').clone().removeClass('d-none');
                addlineIndex++;

                div.find('input, select, textarea').each(function (el) {
                    let name = $(this).attr('name');
                    name = name.replace('[]', '[' + addlineIndex + ']');
                    $(this).attr('name', name);
                });

                div.find("input").val("");
                $('#placeNewRow').before(div);
            });

            $(document.body).on('click', '.remove-line', function () {
                $(this).closest('.row, tr').remove();
                recalculateInvoiceData();
            });

            ['#dp1', '#dp2'].forEach(function (el) {
                initDatepicker(el);
            });

            function uid() {
                return Date.now().toString(36) + Math.random().toString(36).substr(2);
            }

            function showHideOtherCurrencyData() {
                if ($('#currency_id').val() == 1) {
                    $('.currencyData').hide();
                } else {
                    $('.currencyData').show();
                    var currencyName = $('#currency_id').find("option:selected").text();
                    $('#invoice_curency_name').text('Kopā ' + currencyName);
                }
            }

            function setCurrencyRateForBaseCurrency() {
                if ($('#currency_id').val() == 1) {
                    $('#currency_rate').val(1);
                    $('#currency_rate').trigger('change');
                }
            }

            $('#invoicetype_id').on('change', function () {
                if ($(this).val() == 3) {
                    $('#ppr_fields').removeClass('d-none');
                } else {
                    $('#ppr_fields').addClass('d-none');
                }
            });

            window.addEventListener('contentChanged', event => {
                initDatepicker('.date');
                recalculateInvoiceData();
                showHideOtherCurrencyData();
                recalculateInvoiceData();
            });
        });
    </script>
</div>
