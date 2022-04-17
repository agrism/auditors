<div>
    <div>
        Root {{$total}}
        <hr>
        {{$activeComponent}}
        <button wire:click="setActiveA()">A</button>
        <button wire:click="setActiveB()">B</button>
        <h1 style="background-color: magenta; height: 300px;width: 300px;">
            @if($activeComponent == 'A')
                <livewire:test-a color="green"></livewire:test-a>
            @elseif($activeComponent == 'B')
                <livewire:test-a  color="yellow"></livewire:test-a>
            @endif
        </h1>
    </div>

</div>
