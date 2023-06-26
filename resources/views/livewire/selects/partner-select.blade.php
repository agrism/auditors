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
                        <label for="" style="font-size: 12px;display: block;">Name @if($selectedPartnerName)
                                <a style="display: block; float: right" href="https://www.firmas.lv/lv/uznemumi/meklet?q={{$selectedPartnerName}}&search%5Bwhere%5D=name" target="_blank">Pārbaudīt firmas.lv</a>
                            @endif
                            <br><span style="color: green">VALID: Zeme, SIA or Bērziņš Dainis</span><br><span style="color:red;text-decoration: line-through;">NOT VALID: SIA Zeme or Dainis Bērziņš</span>

                        </label>
                        <input type="text" class="form-control @error('selectedPartnerName')is-invalid @enderror"
                               placeholder="Partner name"
                               aria-describedby="basic-addon1" wire:model="selectedPartnerName">
                        @error('selectedPartnerName') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-1">
                        <label for="" style="font-size: 12px; display: block">Reg.No
                            @if($selectedPartnerRegNo)
                                <a style="display: block; float: right" href="https://www.firmas.lv/lv/uznemumi/meklet?q={{$selectedPartnerRegNo}}&search%5Bwhere%5D=code" target="_blank">Pārbaudīt firmas.lv</a>
                            @endif
                        </label>
                        <input type="text" class="form-control @error('selectedPartnerRegNo')is-invalid @enderror"
                               placeholder="Reg. No" aria-describedby="basic-addon1"
                               wire:model="selectedPartnerRegNo">
                        @error('selectedPartnerRegNo') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-1">
                        <label for="" style="font-size: 12px; display: block">VAT No
                            @if($selectedPartnerVatNo)
                                <?php
                                $countryCode = preg_replace('/[^A-Z]/', '', substr(trim($selectedPartnerVatNo), 0, 2));
                                if(strlen($countryCode) === 2){
                                    $number = substr(trim($selectedPartnerVatNo), 2);
                                    if($number){
                                        ?>
                                        <a style="display: block; float: right" href="https://ec.europa.eu/taxation_customs/vies/viesquer.do?ms={{$countryCode}}&iso={{$countryCode}}&vat={{$number}}" target="_blank">Pārbaudīt ec.europa.eu</a>
                                        <?php
                                    }
                                }
                                ?>
{{--                            <a style="display: block; float: right" href="https://www.firmas.lv/results?srch={{$selectedPartnerVatNo}}&exact=" target="_blank">Pārbaudīt firmas.lv</a>--}}
                            @endif
                        </label>

                        <input type="text" class="form-control @error('selectedPartnerVatNo')is-invalid @enderror"
                               placeholder="VAT No." aria-describedby="basic-addon1"
                               wire:model="selectedPartnerVatNo">
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
                    <hr style="color: white">
                    <div class="mb-1">
                        <label for="" style="font-size: 12px;">Bank name</label>
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
                        <label for="" style="font-size: 12px;">Bank Account number</label>
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