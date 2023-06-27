@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<x-input-group label="Title" name="title" value="{{isset($company) ? $company['title'] : null }}" placeholder="Title"></x-input-group>
<x-input-group label="Address" name="address" value="{{isset($company) ? $company['address'] : null }}" placeholder="Address"></x-input-group>
<x-input-group label="Reg.No" name="registration_number" value="{{isset($company) ? $company['registration_number'] : null }}" placeholder="registration no"></x-input-group>
<hr>

<x-input-group label="Default bank" name="bank" value="{{isset($company) ? $company['bank'] : null }}" placeholder="Default bank"></x-input-group>
<x-input-group label="SWIFT" name="swift" value="{{isset($company) ? $company['swift'] : null }}" placeholder="SWIFT"></x-input-group>
<x-input-group label="Account number" name="account_number" value="{{isset($company) ? $company['account_number'] : null }}" placeholder="Account number"></x-input-group>
<hr>



@if(isset($company) && isset($company['vatNumbers']) )
    @foreach($company['vatNumbers'] as $vat)
        <x-input-group label="VAT number" name="vat_number[]" value="{{isset($vat) ? $vat['vat_number'] : null }}" placeholder="VAT number">
            <input type="hidden" name="vat_id[]" value="{{$vat->id}}">
            <span class="input-group-text" role="button">
                <span class="pointer fa fa-remove remove-vat-number"></span>
            </span>
        </x-input-group>
    @endforeach
@endif


{{--blank to clone--}}
<div class="vat_number_div d-none">
    <x-input-group label="VAT number" name="vat_number[]" value="" placeholder="VAT number">
        <span class="input-group-text" role="button">
            <span class="pointer fa fa-remove remove-vat-number"></span>
        </span>
    </x-input-group>
</div>


<div id="div1"></div>

<div class="form-group mt-3 mb-3">
    <label class="col-sm-2 control-label">
        <div class="btn btn-primary fa-plus fa" id="addVatNuber"></div>
    </label>
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
                $(this).parent().parent().parent().remove();
            }
        });

        $('#addVatNuber').trigger('click');
    });
</script>