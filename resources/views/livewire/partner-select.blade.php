<div>
    <div class="input-group" style="width: 100%">
        <select wire:model="selectedPartnerId" class="form-control form-control-sm" name="partner_id">
            @foreach($partners ?? [] as $partner)
                <option value="{{$partner['id']}}">{{$partner['name']}}</option>
            @endforeach
        </select>
        <span class="input-group-text1" id="basic-addon1" data-bs-toggle="modal" data-bs-target="#partnerEditModal"
              wire:click="edit({{ $selectedPartnerId }})">
            <div type="button" class="btn btn-xs fa fa-edit" style="cursor: pointer;"></div>
        </span>
    </div>


    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="partnerEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header info">
                    <h4 class="modal-title">{{ $selectedPartnerId > 0 ? 'Edit' : 'Create' }} Partner</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style1="margin-left: 15px;margin-right: 15px">
                    <div class="mb-1">
                        <label for="" style="font-size: 12px;">Name</label>
                        <input type="text" class="form-control @error('selectedPartnerName')is-invalid @enderror" placeholder="Partner name"
                               aria-describedby="basic-addon1" wire:model.defer="selectedPartnerName">
                        @error('selectedPartnerName') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-1">
                        <label for="" style="font-size: 12px;">Reg.No</label>
                        <input type="text" class="form-control @error('selectedPartnerRegNo')is-invalid @enderror" placeholder="Reg. No" aria-describedby="basic-addon1"
                               wire:model.defer="selectedPartnerRegNo">
                        @error('selectedPartnerRegNo') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-1">
                        <label for="" style="font-size: 12px;">VAT No</label>
                        <input type="text" class="form-control @error('selectedPartnerVatNo')is-invalid @enderror" placeholder="VAT No." aria-describedby="basic-addon1"
                               wire:model.defer="selectedPartnerVatNo">
                        @error('selectedPartnerVatNo') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-1">
                        <label for="" style="font-size: 12px;">Address</label>
                        <input type="text" class="form-control @error('selectedPartnerAddress')is-invalid @enderror" placeholder="Address" aria-describedby="basic-addon1"
                               wire:model.defer="selectedPartnerAddress">
                        @error('selectedPartnerAddress') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click.prevent="save()">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>

@section('js')
    @parent
    <script type="text/javascript">
        window.livewire.on('modalSave', () => {
            $('#partnerEditModal').modal('hide');
        });
    </script>
@stop
