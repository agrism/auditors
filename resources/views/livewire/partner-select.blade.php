<div>
    <div class="input-group" style="width: 100%">
        <select wire:model="selectedPartnerId" class="form-control" name="partner_id">
            @foreach($partners ?? [] as $partner)
            <option value="{{$partner['id']}}">{{$partner['name']}}</option>
            @endforeach
        </select>
            <span class="input-group-btn" style="cursor: pointer;" id="basic-addon1" data-toggle="modal" data-target="#exampleModal" wire:click="edit({{ $selectedPartnerId }})">
                <button type="button" class="btn btn-default">...</button>
            </span>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header info">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ $selectedPartnerId > 0 ? 'Edit' : 'Create' }} Partner</h4>
                </div>
                <div class="modal-body" style="margin-left: 15px;margin-right: 15px">
                        <div class="form-group">
                            <label for="first_name" style="font-size: 12px;">Name</label>
                            <input type="text" class="form-control" placeholder="Partner name" aria-describedby="basic-addon1" wire:model.defer="selectedPartnerName">
                            @error('selectedPartnerName') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="first_name" style="font-size: 12px;">Reg.No</label>
                            <input type="text" class="form-control" placeholder="Reg. No" aria-describedby="basic-addon1" wire:model.defer="selectedPartnerRegNo">
                            @error('selectedPartnerRegNo') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="first_name" style="font-size: 12px;">VAT No</label>
                            <input type="text" class="form-control" placeholder="VAT No." aria-describedby="basic-addon1" wire:model.defer="selectedPartnerVatNo">
                            @error('selectedPartnerVatNo') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="first_name" style="font-size: 12px;">Address</label>
                            <input type="text" class="form-control" placeholder="Address" aria-describedby="basic-addon1" wire:model.defer="selectedPartnerAddress">
                            @error('selectedPartnerAddress') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary close-modal" wire:click.prevent="save()">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>

@section('js')
    @parent
        <script type="text/javascript">
            window.livewire.on('modalSave', () => {
            $('#exampleModal').modal('hide');
        });
    </script>
@stop
