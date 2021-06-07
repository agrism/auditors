@props([
    'title' => 'Alert',
    'titleClass' => '',
    'id' => uniqid(),
    'confirmAction' => 'submit',
    'cancelAction' => 'cancel',
    'cancelActionLabel' => 'Cancel',
    'confirmActionLabel' => 'Continue',
    'confirmActionClass' => 'btn-primary'
])


<div wire:ignore.self class="modal fade" id="{{$id}}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header {{$titleClass}}">
                <h5 class="modal-title" >{{$title}}</h5>
                <button type="button" class="btn-close" aria-label="Close" wire:click="{{$cancelAction}}"></button>
            </div>
            <div class="modal-body">
                {{$slot}}
            </div>
            <div class="modal-footer">
                <div class="btn btn-secondary" wire:click="{{$cancelAction}}">{{$cancelActionLabel}}</div>
                <div class="btn {{$confirmActionClass}}"
                     wire:click="{{$confirmAction}}"
                >{{$confirmActionLabel}}</div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('closeModal_{{$id}}', event => {
        console.log('closing modal #'+'{{$id}}')
        $('#{{$id}}').modal('hide');
    })

    window.addEventListener('openModal_{{$id}}', event => {
        $('#{{$id}}').modal('show');
    })
</script>


