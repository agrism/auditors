<div>
    <div style="background-color: {{$color}}; height: 300px;width: 150px;">
        <h1>c-A</h1>
        {{$cId}}

        <input type="text" wire:model="number">
{{--        <input type="date" class="date">--}}
        <input type="text" class="date">
        <button wire:click="$emitUp('addNumber', {{$number}})" >Add</button>
    </div>


    <script>
        console.log('A')
        $('.date').datepicker();
    </script>
</div>

