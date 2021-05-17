<div>
    <div wire:loading style="position: absolute">
        <x-loading loading="true"></x-loading>
    </div>

    <div class="col-lg-12">
        @if(!$showInvoiceFom)
            <div class="card card-default">
                <div class="card-header">
                    Invoice list
                    {{--                    <a href="{{ url(route('client.invoices.create'))}}"--}}
                    {{--                                    class="btn btn-success btn-sm fa-plus fa"></a>--}}

                    <span class="" role="button"
                          wire:click="openNewInvoice">
                        <span class="fa-plus fa"></span>
                    </span>

                    <a href="{{route('client.invoices.index', ['export'=> 'xls'])}}">
                        <div class="fa fa-file-excel-o"
                             data-bs-toggle="tooltip" data-bs-placement="left" title="EXPORT temporary not working"
                             style="position:fixed;right:0px;padding: 11px; border: 1px solid black;cursor: pointer; background-color: #b6e8fa"></div>
                    </a>
                    <!-- /.card-heading -->

                    {{--                {{$activeInvoiceId}}--}}

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr style="background-color: lightblue;">
                                    <td style="padding: 0;">
                                    </td>
                                    <td style="padding: 2px 0;max-width: 150px;min-width: 150px;">
                                        <input type="text"
                                               wire:model="filter.dateFrom"
                                               class="form-control form-control-sm"
                                               readonly
                                               id="dp3"
                                               autocomplete="off"
                                               style="font-size: 11px;padding: 0 8px; width: 50%; float: left"
                                               onchange="this.dispatchEvent(new InputEvent('input'))"
                                        >
                                        <input type="text"
                                               wire:model="filter.dateTo"
                                               class="form-control form-control-sm"
                                               readonly
                                               id="dp4"
                                               autocomplete="off"
                                               style="font-size: 11px;padding: 0 8px; width: 50%;"
                                               onchange="this.dispatchEvent(new InputEvent('input'))"
                                        >
                                    </td>
                                    <td style="padding: 2px 0">
                                        <select wire:model="filter.typeId"
                                                class="form-control form-control-sm"
                                                style="font-size: 11px;padding: 0 8px"
                                                placeholder="Select type">
                                            <option value="">-</option>
                                            @foreach($invoicetypes as $type)
                                                <option value="{{$type->id}}"
                                                        @if($type->id === $filter['typeId']) selected @endif>{{$type->title}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="padding: 2px 0;">
                                        <select wire:model="filter.structId"
                                                class="form-control form-control-sm"
                                                style="font-size: 11px;padding: 0 8px"
                                                placeholder="Select type">
                                            <option value="">-</option>
                                            @foreach($structuralunits as $type)
                                                <option value="{{$type->id}}"
                                                        @if($type->id === $filter['structId']) selected @endif>{{$type->title}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="padding: 2px 0">
                                        <select wire:model="filter.partnerId"
                                                class="form-control form-control-sm "
                                                style="font-size: 11px;padding: 0 8px"
                                                placeholder="Select partner">
                                            <option value="">-</option>
                                            @foreach($partners as $partner)
                                                <option value="{{$partner->id}}"
                                                        @if($partner->id === $filter['partnerId']) selected @endif>{{$partner->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="padding: 2px 0">
                                        <input type="text"
                                               wire:model.debounce.500ms="filter.details"
                                               class="form-control form-control-sm"
                                               style="font-size: 11px;padding: 0 8px; width: 90%;float: left"
                                        >
                                        <span class="fa fa-close text-center"
                                              style="padding-left: 7px"
                                              role="button"
                                              wire:click="clearFilterForm"
                                        ></span>
                                    </td>
                                    <td style="padding: 2px 10px">

                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    {{--                            <th><x-column-title column="id" :sortColumn="$sortColumn" :sortDirection="$sortDirection" title="ID"></x-column-title></th>--}}
                                    <th>
                                        <x-column-title column="number" :sortColumn="$sortColumn"
                                                        :sortDirection="$sortDirection" title="Number"></x-column-title>
                                    </th>
                                    <th>
                                        <x-column-title column="date" :sortColumn="$sortColumn"
                                                        :sortDirection="$sortDirection" title="Date"></x-column-title>
                                    </th>
                                    <th>
                                        <x-column-title column="invoicetypename" :sortColumn="$sortColumn"
                                                        :sortDirection="$sortDirection" title="Type"></x-column-title>
                                    </th>
                                    <th>
                                        <x-column-title column="structuralunitname" :sortColumn="$sortColumn"
                                                        :sortDirection="$sortDirection"
                                                        title="Structural unit"></x-column-title>
                                    </th>
                                    <th>
                                        <x-column-title column="partnername" :sortColumn="$sortColumn"
                                                        :sortDirection="$sortDirection"
                                                        title="Partner"></x-column-title>
                                    </th>
                                    <th>
                                        <x-column-title column="details_self" :sortColumn="$sortColumn"
                                                        :sortDirection="$sortDirection"
                                                        title="Details"></x-column-title>
                                    </th>
                                    <th>
                                        <x-column-title column="currency_name" :sortColumn="$sortColumn"
                                                        :sortDirection="$sortDirection"
                                                        title="Currency"></x-column-title>
                                    </th>
                                    <th>
                                        <x-column-title column="amount_total" :sortColumn="$sortColumn"
                                                        :sortDirection="$sortDirection" title="Amount"></x-column-title>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>


                                @foreach($invoices as $invoice)
                                    <tr class="line text-truncate {{ (preg_match('/copy/',$invoice->number)) ? 'bg-warning' : null }}"
                                        wire:click="setActiveInvoiceId({{$invoice->id}})"
                                        style="cursor: pointer">
                                        {{--                                <td>{{ $invoice->id}}</td>--}}
                                        <td id="td{{$invoice->id}}">{{ $invoice->number}}</td>
                                        <td class="text-truncate">

                                            {{ $invoice->date}}

                                            {{--                                    @if(isset($invoice) && $invoice->isClosedForEdit)--}}
                                            {{--                                        --}}{{--                                    @if(isset($invoice) )--}}
                                            {{--                                        <i class="fa fa-lock"></i>--}}
                                            {{--                                    @endif--}}

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

                                        <td class="text-truncate">{{ $partnername }}</td>
                                        <td class="text-truncate">{{ $invoice->details_self}}</td>
                                        <td class="text-center text-truncate">{{ $invoice->currency_name}}</td>
                                        <td class="text-end text-truncate">{{ number_format($invoice->amount_total, 2)}}</td>

                                    </tr>
                                    <tr class="@if($invoice->id !== $activeInvoiceId) d-none @endif actions"
                                        style="background-color: #c4c4c4">
                                        <td colspan="100">
                                            <div class="actionOptionns text-center"
                                                 style="z-index: 2; position:relative;">

                                                {{--                                            <div class="fa fa-calculator" style="cursor: pointer"--}}
                                                {{--                                                 wire:click="openModal({{$invoice->id}})">--}}

                                                {{--                                            </div>--}}

                                                {{--                                            <div class="text-warning fa fa-file fa-2x showButton1"--}}
                                                {{--                                                 style="cursor: pointer"--}}
                                                {{--                                                 data-toggle1="tooltip" title="{{ _("View") }}" data-placement="top"--}}
                                                {{--                                                 data-bs-toggle="modal"--}}
                                                {{--                                                 data-bs-target="#myModalShow"--}}
                                                {{--                                                 action-url="{{ url(route('client.invoices.show', $invoice->id))}}"></div>--}}

                                                <span style="margin: 10px;">
                                                <a href="{{route('client.invoices.show', [ $invoice->id, 'locale'=> 'lv'])}}"><span
                                                            class="fa fa-file-pdf-o fa-2x">LV</span></a>

                                            </span>
                                                <span style="margin: 10px;">
                                                <a href="{{route('client.invoices.show', [ $invoice->id, 'locale'=> 'en'])}}"><span
                                                            class="fa fa-file-pdf-o fa-2x">EN</span></a>
                                            </span>


                                                {{--                                            <div class="text-info fa-2x fa fa-copy copyButton1"--}}
                                                {{--                                                 style="cursor: pointer"--}}
                                                {{--                                                 data-toggle1="tooltip" title="{{ _("Copy") }}" data-placement="top"--}}
                                                {{--                                                 data-toggle="modal"--}}
                                                {{--                                                 data-target="#myModalCopy"--}}
                                                {{--                                                 action-url="{{ url(route('client.invoices.copy', $invoice->id))}}"></div>--}}

                                                <span style="margin: 10px;">
{{--                                                <a href="{{route('client.invoices.copy', [ $invoice->id])}}">--}}
                                                    <span
                                                            class="fa fa-copy fa-2x"
                                                            wire:click="copyInvoice"
                                                    ></span>
{{--                                                </a>--}}
                                            </span>

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
                                                         data-toggle1="tooltip" title="{{ _("Lock") }}"
                                                         data-placement="top"
                                                         data-toggle="modal"
                                                         data-target="#myModalLock"
                                                         invoice-id="{{ $invoice->id }}"
                                                         current-invoice-href="{{route('client.invoices.getCurrentInvoice', $invoice->id ) }}"
                                                         edit-invoice-number-href="{{route('client.invoices.updateInvoiceNumber', $invoice->id ) }}"
                                                         action-url="{{ url(route('client.invoices.lock', $invoice->id))}}"></div>

                                                    {{-- 	<a href="{{ url(route('client.invoices.lock', $invoice->id))}}"><div class="btn btn-info btn-xs fa   fa-unlock"></div></a> --}}

                                                    {{--                                                    <a href="{{ url(route('client.invoices.edit',  $invoice->id))}}">--}}
                                                    <div class="text-success fa-md fa-edit fa fa-2x"
                                                         data-toggle1="tooltip" title="{{_("Edit")}}"
                                                         data-placement="top"
                                                         wire:click="$set('showInvoiceFom', {{!$showInvoiceFom}})"
                                                         style="cursor: pointer"
                                                    ></div>
                                                    {{--                                                    </a>--}}

                                                    <div type="button"
                                                         style="cursor: pointer"
                                                         class="text-danger fa-remove fa fa-2x deleteButton1"
                                                         data-toggle1="tooltip" title="{{_("Delete")}}"
                                                         data-placement="top"
                                                         data-toggle="modal"
                                                         data-target="#myModal"
                                                         action-url="{{ url(route('client.invoices.destroy',  [$invoice->id,'method'=>'delete']))}}"
                                                         wire:click="deleteInvoice"
                                                    ></div>
                                                    {{-- <a href="{{ url(route('client.invoices.destroy',  [$invoice->id,'method'=>'delete']))}}"><div class="btn btn-danger btn-xs fa-remove fa"></div></a> --}}
                                                @endif
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $invoices->links() }}


                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
            </div>
        @else
            <div>
                <livewire:invoice-form :invoiceId="$activeInvoiceId"></livewire:invoice-form>
            </div>
        @endif
    </div>

</div>


{{--    $showModal: {{$showModal}}--}}
{{--    @if($showModal)--}}
{{--        <x-modal action="parentAction">--}}
{{--            <h6>Select locale:</h6>--}}
{{--            <div class="form-check">--}}
{{--                <input class="form-check-input" type="radio" name="selectedLocale" value="lv" id="localeLv"  @if(($invoicePrintLocale ?? null) =='lv') checked="checked" @endif>--}}
{{--                <label class="form-check-label" for="localeLv">LV</label>--}}
{{--            </div>--}}
{{--            <div class="form-check">--}}
{{--                <input class="form-check-input" type="radio" name="selectedLocale" value="en" id="localeEn" @if(($invoicePrintLocale ?? null) == 'en') checked="checked" @endif>--}}
{{--                <label class="form-check-label" for="localeEn">EN</label>--}}
{{--            </div>--}}
{{--            <hr>--}}

{{--            <h6>Select action:</h6>--}}
{{--            <div class="form-check">--}}
{{--                <input class="form-check-input" type="radio" name="selectedAction" value="html" id="actionHtml" checked="checked">--}}
{{--                <label class="form-check-label" for="actionHtml">Show HTML</label>--}}
{{--            </div>--}}
{{--            <div class="form-check">--}}
{{--                <input class="form-check-input" type="radio" name="selectedAction" value="pdf" id="actionPdf">--}}
{{--                <label class="form-check-label" for="actionPdf">download PDF</label>--}}
{{--            </div>--}}
{{--            <hr>--}}
{{--            <button class="btn-default">Save</button>--}}
{{--        </x-modal>--}}
{{--    @endif--}}
