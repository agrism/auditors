<div>
    <div wire:loading style="position: absolute">
        <x-loading loading="true"></x-loading>
    </div>

    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-header">
                Cash Expense No: <strong>{{$this->get()->no ?? ''}}</strong>
                <div style="float: right">
                    @if($this->get()->id ?? null)
                    <span style="margin: 10px;">
                                                <a href="{{route('client.cash-expenses.show', [ $this->get()->id ?? null, 'locale'=> 'lv'])}}" target="_blank"><span
                                                            class="fa fa-file-pdf-o fa-2x"></span></a>
                                            </span>
                        @endif
                </div>
            </div>
            <div class="card-body">

                {{--                {{json_encode($this->get())}}--}}

                <div class="row">
                    {{--                    <div class="col-sm-6">--}}
                    {{--                        <label for="number" class="custom border-label-flt  text-danger">Date</label>--}}
                    {{--                        {!! Form::text('number', $this->get()->date , ['class'=>'form-control form-control-sm date', 'placeholder'=>'Date', 'readonly'] ) !!}--}}
                    {{--                    </div>--}}


                    <div class="col-sm-6">
                        <label for="" style="font-size: 12px;">Date {{$cashExpense['date'] ?? ''}}</label>
                        <input type="text" class="form-control date @error('cashExpense.date')is-invalid @enderror"
                               placeholder="Date"
                               readonly
                               aria-describedby="basic-addon1"
                               onchange="this.dispatchEvent(new InputEvent('input'))"
                               wire:model="cashExpense.date">
                        @error('cashExpense.date') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>


                    <div class="col-sm-6">
                        <label for="" style="font-size: 12px;">No</label>
                        <input type="text" class="form-control @error('cashExpense.no')is-invalid @enderror"
                               placeholder="No"
                               aria-describedby="basic-addon1"
                               wire:model.lazy="cashExpense.no">
                        @error('cashExpense.no') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>


                    <div class="row">
                        <div class="col-sm-6">
                            <label for="number" class="custom border-label-flt  text-danger">Person</label>
                            <livewire:employee-select
                                    name="employee_id"
                                    onchange="this.dispatchEvent(new InputEvent('input'))"
                                    key="{{ now() }}"
                                    :selectedEmployeeId="$cashExpense['employee_id']"
                            />
                            @error('cashExpense.employee_id') <small class="text-danger error">{{ $message }}</small>@enderror
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <th>No</th>
                        <th>Date</th>
                        <th>Partner</th>
                        {{--                        <th>VAT no</th>--}}
                        <th>Description</th>
                        {{--                        <th>Doc.No</th>--}}
                        <th>Account</th>
                        <th>Budget</th>
                        <th style="text-align:right;">Without VAT</th>
                        <th style="text-align:right;">VAT</th>
                        <th style="text-align:right;">Amount</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        <?php $total = 0.00;?>
                        <?php $totalVat = 0.00;?>
                        <?php $totalWithoutVat = 0.00;?>
                        @foreach($this->get()->lines ?? [] as $line)
                            <?php $total += floatval(preg_replace('/[^0-9.]/',
                                '',
                                $line->amount_with_vat));?>
                            <?php $totalVat += floatval(preg_replace('/[^0-9.]/',
                                '',
                                $line->amount_vat));?>
                            <?php $totalWithoutVat += floatval(preg_replace('/[^0-9.]/',
                                '',
                                $line->amount_without_vat));?>
                            <tr>
                                <td class="pt-1 pb-0">{{$line->no}}</td>
                                <td class="pt-1 pb-0">{{$line->date}}</td>
                                <td class="pt-1 pb-0">{{$line->partner_name}}</td>
                                {{--                                <td class="pt-1 pb-0">{{$line->partner_vat_number}}</td>--}}
                                <td class="pt-1 pb-0">{{$line->description}}</td>
                                {{--                                <td class="pt-1 pb-0">{{$line->document_no}}</td>--}}
                                <td class="pt-1 pb-0">{{$line->account_code ?? ''}}</td>
                                <td class="pt-1 pb-0">{{$line->budget_code}}</td>
                                <td class="pt-1 pb-0"
                                    style="text-align:right; padding-right: 20px;">{{$line->amount_without_vat}}</td>
                                <td class="pt-1 pb-0"
                                    style="text-align:right; padding-right: 20px;">{{$line->amount_vat}}</td>
                                <td class="pt-1 pb-0"
                                    style="text-align:right; padding-right: 20px;">{{$line->amount_with_vat}}</td>
                                <td class="pt-1 pb-0">
                                    <span
                                            wire:click="openEdit({{$line->id}})"
                                            role="button"
                                    >
                                        <i class="fa fa-edit"></i>
                                    </span>
                                    <span
                                            wire:click="openDelete({{$line->id}})"
                                            role="button"
                                    >
                                        <i class="fa fa-close" style="color: red"></i>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <thead>
                        <tr>
                            <th colspan="6"></th>
                            <th style="text-align:right; padding-right: 20px;">{{number_format($totalWithoutVat, 2)}}</th>
                            <th style="text-align:right; padding-right: 20px;">{{number_format($totalVat, 2)}}</th>
                            <th style="text-align:right; padding-right: 20px;">{{number_format($total, 2)}}</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>

                    <?php //echo '<pre>'; print_r($cashExpense); echo '</pre>' ?>
                    {{--<?php echo '<pre>'; print_r($expenseLine); echo '</pre>' ?>--}}

                    <div class="btn btn-success btn-sm"
                         wire:click="expenseLineOpen">
                        Add expense:
                        <span class="fa-plus fa"></span>
                    </div>


                </div>
                <div class="card-footer">
                    <div class="btn btn-success" role="button" wire:click="close">Close</div>
                </div>

            </div>
        </div>

        {{--    MODAL--}}

        <script>
            initDatepicker('.date');
                // var picker = new Pikaday({
                //     field: document.querySelector('.date') ,
                //     format: 'dd.mm.YYYY',
                // });
        </script>

        <x-modal id="expense_line_delete"
                 title="Delete expense"
                 titleClass="bg-danger text-white"
                 confirmAction="expenseLineDeleteConfirm"
                 cancelAction="expenseLineDeleteCancel"
                 confirmActionClass="btn-danger"
                 confirmActionLabel="Delete"
        >
            Confirm to delete?
        </x-modal>

        <x-modal id="expense_line"
                 title="{{ $expenseLine['id'] ? 'Edit' : 'Create' }} expense"
                 titleClass="bg-primary text-white"
                 confirmAction="expenseLineConfirm"
                 cancelAction="expenseLineCancel"
                 confirmActionClass="btn-primary"
                 confirmActionLabel="{{ $expenseLine['id'] ? 'Update' : 'Insert' }} "
        >
            <div class="mb-1 row">
                <div class="col">
                    <label for="" style="font-size: 12px;">Order No.</label>
                    <input type="text" class="form-control @error('expenseLine.no')is-invalid @enderror"
                           placeholder="Order no."
                           aria-describedby="basic-addon1" wire:model.defer="expenseLine.no">
                    @error('expenseLine.no') <small class="text-danger error">{{ $message }}</small>@enderror
                </div>
                <div class="col">
                    <label for="" style="font-size: 12px;">Date</label>
                    <input type="text" class="date form-control @error('expenseLine.date')is-invalid @enderror"
                           readonly
                           placeholder="Date"
                           onchange="this.dispatchEvent(new InputEvent('input'))"
                           aria-describedby="basic-addon1" wire:model="expenseLine.date">
                    @error('expenseLine.date') <small class="text-danger error">{{ $message }}</small>@enderror
                </div>
                <div class="col">
                    <label for="" style="font-size: 12px;">Document No.</label>
                    <input type="text" class="form-control @error('expenseLine.document_no')is-invalid @enderror"
                           placeholder="Doc. No"
                           aria-describedby="basic-addon1" wire:model.defer="expenseLine.document_no">
                    @error('expenseLine.document_no') <small class="text-danger error">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="mb-1">
                <label for="" style="font-size: 12px;">Partner</label>
                <livewire:partner-select
                        key="{{ now() }}"
                        :selectedPartnerId="$expenseLine['partner_id']"
                ></livewire:partner-select>
                @error('expenseLine.partner_id') <small class="text-danger error">{{ $message }}</small>@enderror
            </div>

            <div class="mb-1">
                <label for="" style="font-size: 12px;">Description</label>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control @error('expenseLine.description')is-invalid @enderror"
                           placeholder="Description"
                           onchange="this.dispatchEvent(new InputEvent('input'))"
                           aria-describedby="basic-addon1" wire:model.defer="expenseLine.description">
                    @error('expenseLine.description') <small
                            class="text-danger error">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="mb-1 row px-2">
                <div class="col px-1 mt-1">
                    <label for="" style="font-size: 12px;">Amount with VAT</label>
                    <div class="input-group input-group-sm">
                        <input type="text"
                               class="form-control @error('expenseLine.amount_with_vat')is-invalid @enderror"
                               placeholder="0.00"
                               onchange="this.dispatchEvent(new InputEvent('input'))"
                               aria-describedby="basic-addon1" wire:model.lazy="expenseLine.amount_with_vat">
                        @error('expenseLine.amount_with_vat') <small
                                class="text-danger error">{{ $message }}</small>@enderror
                    </div>
                </div>
                <div class="col p-1">
                    <label for="" style="font-size: 12px;">Budget</label>
                    <livewire:budget-select
                            key="{{ now() }}"
                            :selectedBudgetId="$expenseLine['budget_id']"
                    ></livewire:budget-select>
                </div>
                <div class="col p-1">
                    <label for="" style="font-size: 12px;">Account</label>
                    <livewire:account-select
                            key="{{ now() }}"
                            :selectedAccountId="$expenseLine['account_id']"
                    ></livewire:account-select>
                </div>
                <div class="col p-0 mt-1">
                    <label for="" style="font-size: 12px;">VAT formula</label>
                    <div class="input-group input-group-sm">
                        <select class="form-control" key="{{ now() }}" wire:model="expenseLine.vat_calculator_name">
                            @foreach(\App\Services\VatCalculator::factory(100)->getCalculator() as $calcKey => $calcVal)
                                <option value="{{$calcVal}}">{{$calcVal}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="mb-1 row">
                <div class="col-6"><strong class="pull-right">without VAT:</strong></div>
                <div class="col-3"><span class="pull-right">{{number_format(floatval($expenseLine['amount_without_vat']), 2)}}</span></div>

            </div>
            <div class="mb-1 row">
                <div class="col-6"><strong class="pull-right">VAT:</strong></div>
                <div class="col-3"><span class="pull-right">{{number_format(floatval($expenseLine['amount_vat']), 2)}}</span></div>
            </div>
            <div class="mb-1 row">
                <div class="col-6"><strong class="pull-right">with VAT:</strong></div>
                <div class="col-3"><span class="pull-right">{{number_format(floatval($expenseLine['amount_with_vat']), 2)}}</span></div>
            </div>


        </x-modal>
    </div>
</div>