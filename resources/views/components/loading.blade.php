@props([
    'loading' => false
])

<div>
    @if($loading == true)
        <div style="position: fixed;z-index: 1031;top: 50%;left: 50%;transform: translate(-50%, -50%);">

            <div class="spinner-grow text-info"
                 style="width: 5rem;height: 5rem;"
                 role="status"
            >
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    @endif
</div>

