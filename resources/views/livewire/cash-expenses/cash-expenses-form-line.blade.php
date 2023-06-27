<div>
    <div wire:loading style="position: absolute">
        <x-loading loading="true"></x-loading>
    </div>

    <div class="col-lg-12">
        <div class="row">
            <div class="col-1">
                <input type="text" class="form-control" value="{{$no}}">
            </div>
            <div class="col-2">
                <input type="text" class="form-control" value="{{$date}}">
            </div>
            <div class="col-4">
                <livewire:partner-select name="partner_id"
                                         :selectedPartnerId="$partnerId??null"/>

            </div>
            <div class="col">
                <input type="text" class="form-control" value="{{$description}}">
            </div>
            <div class="col">
                <input type="text" class="form-control" value="{{$amount}}">
            </div>
            <div class="col-1">
                <div class="btn-sm btn"
                    wire:click="remove"
                >
                    <span class="fa fa-close" style="color: red"></span>
                </div>
            </div>
        </div>
    </div>
</div>