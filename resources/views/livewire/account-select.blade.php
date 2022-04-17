<div>
    <div wire:loading style="position: absolute">
        <x-loading loading="true"></x-loading>
    </div>

    <div class="input-group input-group-sm" style="width: 100%">
        <select wire:model="selectedAccountId" class="form-control form-control-sm" name="account_id">
            @foreach($accounts ?? [] as $account)
                <option value="{{$account['id']}}">{{$account['code']}}</option>
            @endforeach
        </select>
        <span id="basic-addon1"
              data-bs-toggle="modal"
              role="button"
              wire:click="edit({{ $selectedAccountId }})">
            <div class="input-group-append">
                <span class="input-group-text fa fa-edit"></span>
            </div>
{{--            <div type="button1" class="btn btn-xs fa fa-edit" style="cursor: pointer;"></div>--}}
        </span>
    </div>


    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="accountEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #b6b9ba;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header info">
                    <h4 class="modal-title">{{ $selectedAccountId > 0 ? 'Edit' : 'Create' }} Account</h4>
                    <button type="button" class="btn-close" aria-label="Close" wire:click="cancel()"></button>
                </div>
                <div class="modal-body" style1="margin-left: 15px;margin-right: 15px">
                    <div class="mb-1">
                        <label for="" style="font-size: 14px;">Code</label>
                        <input type="text" class="form-control @error('selectedAccountCode')is-invalid @enderror"
                               placeholder="Account code"
                               aria-describedby="basic-addon1" wire:model.defer="selectedAccountCode">
                        @error('selectedAccountCode') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-1">
                        <label for="" style="font-size: 14px;">Name</label>
                        <input type="text" class="form-control @error('selectedAccountName')is-invalid @enderror"
                               placeholder="Account name"
                               aria-describedby="basic-addon1" wire:model.defer="selectedAccountName">
                        @error('selectedAccountName') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cancel()">Close</button>
                    <button type="button" class="btn btn-primary" wire:click.prevent="save()">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <script type="text/javascript">
        window.addEventListener('account_modal_open', event => {
            $('#accountEditModal').modal('show');
        })

        window.addEventListener('account_modal_close', () => {
            $('#accountEditModal').modal('hide');
        });
    </script>

</div>