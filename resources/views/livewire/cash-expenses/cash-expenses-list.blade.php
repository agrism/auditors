<div>
    <div wire:loading style="position: absolute">
        <x-loading loading="true"></x-loading>
    </div>

    @if(!$this->isEditMode())
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header">
                    Cash expenses {{$editMode}}

                                    <span class="" role="button"

                                          wire:click="new()"
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
                                               wire:model="filter.date"
                                               class="form-control form-control-sm"
                                               {{--                                               autocomplete="off"--}}
                                               style=""
                                               onchange="this.dispatchEvent(new InputEvent('input'))"
                                        >
                                    </td>

                                    <td style="padding: 2px 0;">
                                        <input type="text"
                                               wire:model="filter.no"
                                               class="form-control form-control-sm"
                                               autocomplete="off"
                                               style=""
                                               onchange="this.dispatchEvent(new InputEvent('input'))"
                                        >
                                    </td>
                                    <td style="padding: 2px 0;">
                                        <input type="text"
                                               wire:model="filter.name"
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
                                        <x-column-title column="date" :sortColumn="$sortColumn"
                                                        :sortDirection="$sortDirection" title="Date"></x-column-title>
                                    </th>
                                    <th>
                                        <x-column-title column="no" :sortColumn="$sortColumn"
                                                        :sortDirection="$sortDirection" title="No"></x-column-title>
                                    </th>
                                    <th>
                                        <x-column-title column="name" :sortColumn="$sortColumn"
                                                        :sortDirection="$sortDirection"
                                                        title="Person"></x-column-title>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($cashExpenses as $cashExpense)
                                    <tr class="line text-truncate {{ (preg_match('/copy/',$cashExpense->id)) ? 'bg-warning' : null }}"
                                        {{--                                    wire:click="openEdit({{$cashExpense->id}})"--}}
                                        wire:click="setActive({{$cashExpense->id}})"
                                        role="button"
                                    >
                                        <td class="text-truncate">

                                            {{ $cashExpense->date}}

                                        </td>
                                        <td class="text-truncate">
                                            {{ $cashExpense->no}}
                                        </td>
                                        <td class="text-truncate">
                                            {{ $cashExpense->name}}
                                        </td>

                                    </tr>
                                    <tr class="@if($cashExpense->id !== $this->activeId) d-none @endif actions"
                                        style="background-color: #c4c4c4">
                                        <td colspan="100">
                                            <div class="actionOptions text-center"
                                                 style="z-index: 2; position:relative;">

                                            <span style="margin: 10px;">
                                                <a href="{{route('client.cash-expenses.show', [ $cashExpense->id, 'locale'=> 'lv'])}}" target="_blank"><span
                                                            class="fa fa-file-pdf-o fa-2x"></span></a>
                                            </span>
                                                <span style="margin: 10px;">
                                                    <span
                                                            class="fa fa-edit fa-2x"
                                                            role="button"
                                                            wire:click="setEditMode"
                                                    ></span>
                                            </span>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $cashExpenses->links() }}
                        </div>
                        <!-- /.table-responsive -->
                    </div>

                </div>
            </div>
        </div>
    @else
        <livewire:cash-expenses.cash-expenses-form :cashExpenseId="$activeId"></livewire:cash-expenses.cash-expenses-form>
    @endif

</div>