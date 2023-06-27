<div>
    <div wire:loading style="position: absolute">
        <x-loading loading="true"></x-loading>
    </div>

    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-header">
                Partner list

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
                                           wire:model="filter.name"
                                           class="form-control form-control-sm"
                                           {{--                                               autocomplete="off"--}}
                                           style=""
                                           onchange="this.dispatchEvent(new InputEvent('input'))"
                                    >
                                </td>

                                <td style="padding: 2px 0;">
                                    <input type="text"
                                           wire:model="filter.address"
                                           class="form-control form-control-sm"
                                           autocomplete="off"
                                           style=""
                                           onchange="this.dispatchEvent(new InputEvent('input'))"
                                    >
                                </td>
                                <td style="padding: 2px 0;">
                                    <input type="text"
                                           wire:model="filter.registration_number"
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
                                    <x-column-title column="name" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Name"></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="address" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Address"></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="registration_number" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection"
                                                    title="Registration No"></x-column-title>
                                </th>
                            </tr>
                            </thead>
                            <tbody>


                            @foreach($partners as $partner)
                                <tr class="line text-truncate {{ (preg_match('/copy/',$partner->id)) ? 'bg-warning' : null }}"
                                    wire:click="openEdit({{$partner->id}})"
{{--                                    data-bs-toggle="modal"--}}
                                    role="button"
{{--                                    data-bs-target="#partnerEditModal_"--}}
                                >
                                    <td class="text-truncate">

                                        {{ $partner->name}}

                                    </td>
                                    <td class="text-truncate">
                                        {{ $partner->address}}
                                    </td>
                                    <td class="text-truncate">
                                        {{ $partner->registration_number}}
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
                        {{ $partners->links() }}


                    </div>
                    <!-- /.table-responsive -->
                </div>

            </div>
        </div>
    </div>


    <x-modal id="handle_partner"
             title="{{ $active['id'] ? 'Edit' : 'Create' }} Partner"
             titleClass="bg-primary text-white"
             confirmAction="savePartnerConfirm"
             cancelAction="savePartnerCancel"
             confirmActionClass="btn-primary"
             cancelActionLabel="Cancel"
             confirmActionLabel="Save"

    >
        <div class="modal-body" style1="margin-left: 15px;margin-right: 15px">
            <div class="mb-1">
                <label for="" style="font-size: 12px;display: block">Name
                    @if($active['name'])
                        <a style="display: block; float: right" href="https://www.firmas.lv/lv/uznemumi/meklet?q={{$active['name']}}&search%5Bwhere%5D=name" target="_blank">Pārbaudīt firmas.lv</a>
                    @endif
                </label>
                <input type="text" class="form-control @error('active.name')is-invalid @enderror"
                       placeholder="Partner name"
                       aria-describedby="basic-addon1" wire:model="active.name">
                @error('active.name') <small class="text-danger error">{{ $message }}</small>@enderror
            </div>

            <div class="mb-1">
                <label for="" style="font-size: 12px;display: block">Reg.No
                    @if($active['regNo'])
                        <a style="display: block; float: right" href="https://www.firmas.lv/lv/uznemumi/meklet?q={{$active['regNo']}}&search%5Bwhere%5D=code" target="_blank">Pārbaudīt firmas.lv</a>
                    @endif
                </label>
                <input type="text" class="form-control @error('active.regNo')is-invalid @enderror"
                       placeholder="Reg. No" aria-describedby="basic-addon1"
                       wire:model="active.regNo">
                @error('active.regNo') <small class="text-danger error">{{ $message }}</small>@enderror
            </div>
            <div class="mb-1">
                <label for="" style="font-size: 12px; display: block;">VAT No
                    @if($active['vatNo'])
                        <?php
//                        $countryCode = preg_replace('/[^A-Z]/', '', $active['vatNo'] );
                        $countryCode = preg_replace('/[^A-Z]/', '', substr(trim($active['vatNo']), 0, 2));
                        if(strlen($countryCode) === 2){
                        $number = substr(trim($active['vatNo']), 2);
                        if($number){
                        ?>
                        <a style="display: block; float: right" href="https://ec.europa.eu/taxation_customs/vies/viesquer.do?ms={{$countryCode}}&iso={{$countryCode}}&vat={{$number}}" target="_blank">Pārbaudīt ec.europa.eu</a>
                        <?php
                        }
                        }
                        ?>
{{--                        <a style="display: block; float: right" href="https://www.firmas.lv/results?srch={{$active['vatNo']}}&exact=" target="_blank">Pārbaudīt firmas.lv</a>--}}
                    @endif
                </label>
                <input type="text" class="form-control @error('active.vatNo')is-invalid @enderror"
                       placeholder="VAT No." aria-describedby="basic-addon1"
                       wire:model="active.vatNo">
                @error('active.vatNo') <small class="text-danger error">{{ $message }}</small>@enderror
            </div>
            <div class="mb-1">
                <label for="" style="font-size: 12px;">Address</label>
                <input type="text" class="form-control @error('active.address')is-invalid @enderror"
                       placeholder="Address" aria-describedby="basic-addon1"
                       wire:model.defer="active.address">
                @error('active.address') <small
                        class="text-danger error">{{ $message }}</small>@enderror
            </div>
            <br>
            <hr>
            <div class="mb-1">
                <label for="" style="font-size: 12px;">Bank</label>
                <input type="text" class="form-control @error('active.bank')is-invalid @enderror"
                       placeholder="Bank" aria-describedby="basic-addon1"
                       wire:model.defer="active.bank">
                @error('active.bank') <small
                        class="text-danger error">{{ $message }}</small>@enderror
            </div>
            <div class="mb-1">
                <label for="" style="font-size: 12px;">SWIFT</label>
                <input type="text" class="form-control @error('active.swift')is-invalid @enderror"
                       placeholder="SWIFT" aria-describedby="basic-addon1"
                       wire:model.defer="active.swift">
                @error('active.swift') <small
                        class="text-danger error">{{ $message }}</small>@enderror
            </div>
            <div class="mb-1">
                <label for="" style="font-size: 12px;">Bank account</label>
                <input type="text" class="form-control @error('active.accountNumber')is-invalid @enderror"
                       placeholder="Bank account" aria-describedby="basic-addon1"
                       wire:model.defer="active.accountNumber">
                @error('active.accountNumber') <small
                        class="text-danger error">{{ $message }}</small>@enderror
            </div>
        </div>
    </x-modal>
</div>