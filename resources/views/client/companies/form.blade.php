@if (count($errors) > 0)
    <div class="alert alert-danger mb-4 rounded-3">
        <ul class="mb-0 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-6">
        <h6 class="fw-bold text-slate-800 mb-3 pb-2 border-bottom">
            <i class="fa-solid fa-building me-1 text-primary-500"></i> Pamatinformācija
        </h6>
        <x-input-group label="Nosaukums" name="title" value="{{isset($company) ? $company['title'] : null }}" placeholder="Uzņēmuma nosaukums"></x-input-group>
        <x-input-group label="Juridiskā adrese" name="address" value="{{isset($company) ? $company['address'] : null }}" placeholder="Juridiskā adrese"></x-input-group>
        <x-input-group label="Reģistrācijas Nr." name="registration_number" value="{{isset($company) ? $company['registration_number'] : null }}" placeholder="40000000000"></x-input-group>
    </div>

    <div class="col-lg-6">
        <h6 class="fw-bold text-slate-800 mb-3 pb-2 border-bottom">
            <i class="fa-solid fa-building-columns me-1 text-primary-500"></i> Bankas rekvizīti
        </h6>
        <x-input-group label="Noklusējuma banka" name="bank" value="{{isset($company) ? $company['bank'] : null }}" placeholder="Bankas nosaukums"></x-input-group>
        <x-input-group label="SWIFT / BIC" name="swift" value="{{isset($company) ? $company['swift'] : null }}" placeholder="SWIFT kods"></x-input-group>
        <x-input-group label="Bankas konts (IBAN)" name="account_number" value="{{isset($company) ? $company['account_number'] : null }}" placeholder="LV00UNLA0000000000000"></x-input-group>
    </div>

    <div class="col-12">
        <h6 class="fw-bold text-slate-800 mb-3 pb-2 border-bottom d-flex align-items-center justify-content-between">
            <span><i class="fa-solid fa-receipt me-1 text-primary-500"></i> PVN reģistrācijas numuri</span>
            <button type="button" class="btn btn-sm btn-outline-primary py-1 px-3 rounded-pill" id="addVatNuber">
                <i class="fa-solid fa-plus me-1"></i> Pievienot PVN numuru
            </button>
        </h6>

        @if(isset($company) && isset($company['vatNumbers']) )
            @foreach($company['vatNumbers'] as $vat)
                <x-input-group label="PVN Nr." name="vat_number[]" value="{{isset($vat) ? $vat['vat_number'] : null }}" placeholder="LV40000000000">
                    <input type="hidden" name="vat_id[]" value="{{$vat->id}}">
                    <span class="input-group-text bg-white" role="button">
                        <span class="pointer fa-solid fa-trash-can text-danger remove-vat-number"></span>
                    </span>
                </x-input-group>
            @endforeach
        @endif

        {{--blank to clone--}}
        <div class="vat_number_div d-none">
            <x-input-group label="PVN Nr." name="vat_number[]" value="" placeholder="LV40000000000">
                <span class="input-group-text bg-white" role="button">
                    <span class="pointer fa-solid fa-trash-can text-danger remove-vat-number"></span>
                </span>
            </x-input-group>
        </div>

        <div id="div1"></div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#addVatNuber').on('click', function () {
            var clonedDiv = $('.vat_number_div').clone();
            clonedDiv.find('input:text').val('');
            clonedDiv.removeClass('vat_number_div');
            clonedDiv.removeClass('d-none');

            ($('#div1')).before(clonedDiv);
        });

        $(document).on('click', '.remove-vat-number', function () {
            count = $('input[name*="vat_number"]').length;
            if (count > 2) {
                $(this).closest('.input-group').parent().remove();
            }
        });
    });
</script>