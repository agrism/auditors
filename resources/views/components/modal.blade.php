@props([
    'title' => 'modal',
    'id' => null,
    'closeButtonTitle' => 'Close',
    'saveButtonTitle' => 'Save',
    'saveButtonId' => null,
    '$actionUrl' => '/',
    'action' => 'submit',
])


<div>
    <div class="" style="position:absolute;width: 80%;min-height: 300px;background-color: white;opacity: 0.8; border: 1px solid grey;position: fixed;z-index: 1000;top: 50%;left: 50%;transform: translate(-50%, -50%);border-radius: 15px;overflow: hidden;box-shadow: 2px 4px 10px grey;">
        <div style="width: 100%; background-color: #3da1ec; padding: 20px;">
            {{$title}}
        </div>
        <form wire:submit.prevent="{{$action}}">
            <div style="margin: 10px;">
                {!! html_entity_decode($slot) !!}
            </div>
        </form>
    </div>
</div>


