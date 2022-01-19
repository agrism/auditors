<div xmlns:livewire="http://www.w3.org/1999/html">
    <span class="text-gray-700">personal-income</span>
{{--    <div>--}}
{{--        <div id="sample-select" wire:ignore></div>--}}
{{--    </div>--}}


    <livewire:selects.partner-select-v2 name="partner_id"
                             :selectedPartnerId="$partnerId??null"/>
</div>

<script>


{{--VirtualSelect.init({--}}
{{--  ele: '#sample-select',--}}
{{--  options: [--}}
{{--    { label: 'Options 1', value: '1' },--}}
{{--    { label: 'Options 2', value: '2' },--}}
{{--    { label: 'Options 3', value: '3' },--}}
{{--  ],--}}
{{--});--}}

</script>