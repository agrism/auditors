<div>
    <style>
        td {
            padding: 3px 1px !important;
            margin: 0px !important;
        }
    </style>

    <div wire:loading style="position: absolute">
        <x-loading loading="true"></x-loading>
    </div>
    <div>
        <div class="card card-default">
            <div class="card-header">

                <div class="table-responsive">
                    <h5>Company data:</h5>


                    <form action=""
                          wire:submit.prevent="save"
                    >
                        <div>
                            @if (session()->has('message'))
                                <div class="alert alert-success  alert-dismissible fade show" role="alert" onload="">
                                    {{ session('message') }}

                                </div>
                            @endif
                        </div>


                        <div class="row">
                            <div class="col col-sm-12">
                                <label for="title" class="custom">Name</label>
                                <div class="input-group input-group-sm">
                                    <input type="text"
                                           name="title"
                                           wire:model.defer="details.title"
                                           class="form-control form-control-sm @error('details.title')is-invalid @enderror"
                                           placeholder="Input title">
                                    @error('details.title') <small
                                            class="text-danger error">{{ $message }}</small>@enderror
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="title" class="custom">Address</label>
                                <div class="input-group">
                                    <input type="text"
                                           name="title"
                                           wire:model.defer="details.address"
                                           class="form-control form-control-sm @error('details.address')is-invalid @enderror"
                                           placeholder="Input address">
                                    @error('details.address') <small
                                            class="text-danger error">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="reg_no" class="custom">Reg.no</label>
                                <div class="input-group">
                                    <input type="text"
                                           wire:model.defer="details.registration_number"
                                           class="form-control form-control-sm @error('details.registration_number')is-invalid @enderror"
                                           placeholder="reg. no"
                                    >
                                    @error('details.registration_number') <small
                                            class="text-danger error">{{ $message }}</small>@enderror

                                </div>
                            </div>
                        </div>
                        <br>
                        <hr>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="bank" class="custom">Default bank</label>
                                <div class="input-group">
                                    <input type="text"
                                           wire:model.defer="details.bank"
                                           class="form-control form-control-sm @error('details.bank')is-invalid @enderror"
                                           placeholder="bank"
                                    >
                                    @error('details.bank') <small
                                            class="text-danger error">{{ $message }}</small>@enderror

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="swift" class="custom">SWIFT</label>
                                <div class="input-group">
                                    <input type="text"
                                           wire:model.defer="details.swift"
                                           class="form-control form-control-sm @error('details.swift')is-invalid @enderror"
                                           placeholder="swift"
                                    >
                                    @error('details.swift') <small
                                            class="text-danger error">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="account_number" class="custom">Account number</label>
                                <div class="input-group">
                                    <input type="text"
                                           wire:model.defer="details.account_number"
                                           class="form-control form-control-sm @error('details.account_number')is-invalid @enderror"
                                           placeholder="account number"
                                    >
                                    @error('details.account_number') <small
                                            class="text-danger error">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>

                        <br>
                        <hr>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="account_number" class="custom">VAT numbers</label>
                                @foreach($details['vat_numbers'] ?? [] as $id => $number)
                                    <div class="input-group input-group-sm" style="margin-top: 3px;">
                                        <input type="text"
                                               class="form-control"
                                               wire:model.lazy="details.vat_numbers.{{$id}}"
                                        >
                                        <div class="input-group-append"
                                             style="cursor: pointer"
                                             wire:click="removeVatLine('{{$id}}')"
                                        >
                                            <span class="input-group-text  fa fa-remove"></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="">
                    <span class="fa fa-plus btn btn-info btn-xs pull-right"
                          style="margin-top: 3px;"
                          wire:click="addVatLine"
                    ></span>
                        </div>


                        <hr>

                        <div class="row">
                            <div class="col-sm-12 mt-10">
                                <button class="btn btn-success">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        window.addEventListener('alert_remove', event => {
            setTimeout(function () {
                document.querySelector('.alert').style.display = 'none';
            }, 2000);
        })
    </script>
</div>