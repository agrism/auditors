<div>
    <div wire:loading style="position: absolute">
        <x-loading loading="true"></x-loading>
    </div>

    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-header">
                Other payment receivers

                <span class="" role="button"

                      wire:click="openEdit('')"
                      data-bs-toggle="modal"
                      data-bs-target="#partnerEditModal_"
                >
                        <span class="fa-plus fa"></span>
                    </span>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr style="background-color: lightblue;">
                                <td style="padding: 2px 0;">
                                    <input type="text"
                                           wire:model.debounce.500ms="filter.payment_receiver"
                                           class="form-control form-control-sm"
                                           {{--                                               autocomplete="off"--}}
                                           style=""
                                           onchange="this.dispatchEvent(new InputEvent('input'))"
                                    >
                                </td>

                                <td style="padding: 2px 0;">
                                    <input type="text"
                                           wire:model.debounce.500ms="filter.bank"
                                           class="form-control form-control-sm"
                                           autocomplete="off"
                                           style=""
                                           onchange="this.dispatchEvent(new InputEvent('input'))"
                                    >
                                </td>
                                <td style="padding: 2px 0;">
                                    <input type="text"
                                           wire:model.debounce.500ms="filter.swift"
                                           class="form-control form-control-sm"
                                           autocomplete="off"
                                           style=""
                                           onchange="this.dispatchEvent(new InputEvent('input'))"
                                    >
                                </td>
                                <td style="padding: 2px 0;">
                                    <input type="text"
                                           wire:model.debounce.500ms="filter.account_number"
                                           class="form-control form-control-sm"
                                           autocomplete="off"
                                           style=""
                                           onchange="this.dispatchEvent(new InputEvent('input'))"
                                    >
                                </td>
                                <td style="padding: 2px 0;">
                                    <input type="text"
                                           wire:model.debounce.500ms="filter.comment"
                                           class="form-control form-control-sm"
                                           autocomplete="off"
                                           style=""
                                           onchange="this.dispatchEvent(new InputEvent('input'))"
                                    >
                                </td>

                                <td style="padding: 2px 0">
                                        <span class="fa fa-close text-center"
                                              style="padding: 3px"
                                              role="button"
                                              wire:click="clearFilterForm"
                                        ></span>
                                </td>

                            </tr>
                            <tr>
                                <th>
                                    <x-column-title column="receiver_name" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="name"></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="bank" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="bank"></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="swift" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection"
                                                    title="SWIFT"></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="account_number" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection"
                                                    title="account number"></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="comment" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection"
                                                    title="comment"></x-column-title>
                                </th>
                            </tr>
                            </thead>
                            <tbody>


                            @foreach($paymentReceivers as $partner)
                                <tr class="line text-truncate {{ (preg_match('/copy/',$partner->id)) ? 'bg-warning' : null }}"
                                    wire:click="openEdit({{$partner->id}})"
                                    {{--                                    data-bs-toggle="modal"--}}
                                    role="button"
                                        {{--                                    data-bs-target="#partnerEditModal_"--}}
                                >
                                    <td class="text-truncate">

                                        {{ $partner->payment_receiver}}

                                    </td>
                                    <td class="text-truncate">
                                        {{ $partner->bank}}
                                    </td>
                                    <td class="text-truncate">
                                        {{ $partner->swift}}
                                    </td>
                                    <td class="text-truncate">
                                        {{ $partner->account_number}}
                                    </td>
                                    <td class="text-truncate">
                                        {{ $partner->comment}}
                                    </td>

                                </tr>
                                <tr class="@if($partner->id !== 1) d-none @endif actions"
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
                                                <a href="{{route('client.invoices.show', [ $partner->id, 'locale'=> 'lv'])}}"><span
                                                            class="fa fa-file-pdf-o fa-2x">LV</span></a>

                                            </span>
                                            <span style="margin: 10px;">
                                                <a href="{{route('client.invoices.show', [ $partner->id, 'locale'=> 'en'])}}"><span
                                                            class="fa fa-file-pdf-o fa-2x">EN</span></a>
                                            </span>
                                            <span style="margin: 10px;">
                                                    <span
                                                            class="fa fa-copy fa-2x"
                                                            role="button"
                                                            wire:click=""
                                                    ></span>
                                            </span>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $paymentReceivers->links() }}


                    </div>
                    <!-- /.table-responsive -->
                </div>

            </div>
        </div>
    </div>


    <x-modal id="handle_payment_receiver"
             title="{{ $active['id'] ? 'Edit' : 'Create' }} Partner"
             titleClass="bg-primary text-white"
             confirmAction="savePaymentReceiverConfirm"
             cancelAction="savePaymentReceiverCancel"
             confirmActionClass="btn-primary"
             cancelActionLabel="Cancel"
             confirmActionLabel="Save"

    >
        <div class="modal-body" style1="margin-left: 15px;margin-right: 15px">
            <div class="mb-1">
                <label for="" style="font-size: 12px;display: block">Name
                </label>
                <input type="text" class="form-control @error('active.name')is-invalid @enderror"
                       placeholder="name"
                       aria-describedby="basic-addon1" wire:model.lazy="active.payment_receiver">
                @error('active.payment_receiver') <small class="text-danger error">{{ $message }}</small>@enderror
            </div>

            <div class="mb-1">
                <label for="" style="font-size: 12px;display: block">Bank
                </label>
                <input type="text" class="form-control @error('active.bank')is-invalid @enderror"
                       placeholder="bank" aria-describedby="basic-addon1"
                       wire:model.lazy="active.bank">
                @error('active.bank') <small class="text-danger error">{{ $message }}</small>@enderror
            </div>

            <div class="mb-1">
                <label for="" style="font-size: 12px; display: block;">SWIFT
                </label>
                <input type="text" class="form-control @error('active.swift')is-invalid @enderror"
                       placeholder="SWIFT" aria-describedby="basic-addon1"
                       wire:model.lazy="active.swift">
                @error('active.swift') <small class="text-danger error">{{ $message }}</small>@enderror
            </div>
            <div class="mb-1">
                <label for="" style="font-size: 12px;">Bank account</label>
                <input type="text" class="form-control @error('active.account_number')is-invalid @enderror"
                       placeholder="account number" aria-describedby="basic-addon1"
                       wire:model.lazy.defer="active.account_number">
                @error('active.account_number') <small
                        class="text-danger error">{{ $message }}</small>@enderror
            </div>
            <div class="mb-1">
                <label for="" style="font-size: 12px;">Comment</label>
                <input type="text" class="form-control @error('active.comment')is-invalid @enderror"
                       placeholder="comment" aria-describedby="basic-addon1"
                       wire:model.lazy.defer="active.comment">
                @error('active.comment') <small
                        class="text-danger error">{{ $message }}</small>@enderror
            </div>
        </div>
    </x-modal>
</div>