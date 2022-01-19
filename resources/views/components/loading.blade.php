@props([
    'loading' => false
])

<div>
    @if($loading == true)

        <div style="
        z-index: 1030;
          content: '';
          display: block;
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: radial-gradient(rgba(20, 20, 20,.2), rgba(0, 0, 0, .2));
          background: -webkit-radial-gradient(rgba(20, 20, 20,.2), rgba(0, 0, 0,.2));"
        >

        </div>
        <div style="position: fixed;z-index: 1031;top: 50%;left: 50%;transform: translate(-50%, -50%);">

            <div class="spinner-grow text-secondary"
                 style="width: 3rem;height: 3rem;"
                 role="status"
            >
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    @endif
</div>