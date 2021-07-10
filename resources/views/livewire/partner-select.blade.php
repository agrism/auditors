<div>
    <div class="input-group input-group-sm" style="width: 100%">
        <select wire:model="selectedPartnerId" class="form-control form-control-sm" name="partner_id">
            @foreach($partners ?? [] as $partner)
                <option value="{{$partner['id']}}">{{$partner['name']}}</option>
            @endforeach
        </select>
        <span id="basic-addon1"
              data-bs-toggle="modal"
              role="button"
              wire:click="edit({{ $selectedPartnerId }})">
            <div class="input-group-append">
                <span class="input-group-text fa fa-edit"></span>
            </div>
{{--            <div type="button1" class="btn btn-xs fa fa-edit" style="cursor: pointer;"></div>--}}
        </span>
    </div>


    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="partnerEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: #b9d4e2;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header info">
                    <h4 class="modal-title">{{ $selectedPartnerId > 0 ? 'Edit' : 'Create' }} Partner</h4>
                    <button type="button" class="btn-close" aria-label="Close" wire:click="cancel()"></button>
                </div>
                <div class="modal-body" style1="margin-left: 15px;margin-right: 15px">
                    <div class="mb-1">
                        <label for="" style="font-size: 12px;">Name</label>
                        <input type="text" class="form-control @error('selectedPartnerName')is-invalid @enderror"
                               placeholder="Partner name"
                               aria-describedby="basic-addon1" wire:model.defer="selectedPartnerName">
                        @error('selectedPartnerName') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-1">
                        <label for="" style="font-size: 12px;">Reg.No</label>
                        <input type="text" class="form-control @error('selectedPartnerRegNo')is-invalid @enderror"
                               placeholder="Reg. No" aria-describedby="basic-addon1"
                               wire:model.defer="selectedPartnerRegNo">
                        @error('selectedPartnerRegNo') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-1">
                        <label for="" style="font-size: 12px;">VAT No</label>
                        <input type="text" class="form-control @error('selectedPartnerVatNo')is-invalid @enderror"
                               placeholder="VAT No." aria-describedby="basic-addon1"
                               wire:model.defer="selectedPartnerVatNo">
                        @error('selectedPartnerVatNo') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-1">
                        <label for="" style="font-size: 12px;">Address</label>
                        <input type="text" class="form-control @error('selectedPartnerAddress')is-invalid @enderror"
                               placeholder="Address" aria-describedby="basic-addon1"
                               wire:model.defer="selectedPartnerAddress">
                        @error('selectedPartnerAddress') <small
                                class="text-danger error">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-1">
                        <label for="" style="font-size: 12px;">Bank</label>
                        <input type="text" class="form-control @error('selectedPartnerBank')is-invalid @enderror"
                               placeholder="Bank" aria-describedby="basic-addon1"
                               wire:model.defer="selectedPartnerBank">
                        @error('selectedPartnerBank') <small
                                class="text-danger error">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-1">
                        <label for="" style="font-size: 12px;">SWIFT</label>
                        <input type="text" class="form-control @error('selectedPartnerSwift')is-invalid @enderror"
                               placeholder="SWIFT" aria-describedby="basic-addon1"
                               wire:model.defer="selectedPartnerSwift">
                        @error('selectedPartnerSwift') <small
                                class="text-danger error">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-1">
                        <label for="" style="font-size: 12px;">Account number</label>
                        <input type="text" class="form-control @error('selectedPartnerAccountNumber')is-invalid @enderror"
                               placeholder="Account number" aria-describedby="basic-addon1"
                               wire:model.defer="selectedPartnerAccountNumber">
                        @error('selectedPartnerAccountNumber') <small
                                class="text-danger error">{{ $message }}</small>@enderror
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
        window.addEventListener('partner_modal_open', event => {
            $('#partnerEditModal').modal('show');
        })

        window.addEventListener('partner_modal_close', () => {
            $('#partnerEditModal').modal('hide');
        });
    </script>

</div>
