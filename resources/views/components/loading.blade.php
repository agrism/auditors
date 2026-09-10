@props([
    'loading' => false,
    'message' => 'Notiek apstrāde...',
    'submessage' => 'Lūdzu, uzgaidiet...'
])

<div>
    @if($loading == true)
        <div class="eds-loading-overlay">
            <div class="eds-loading-card">
                <div class="eds-spinner"></div>
                <div class="eds-loading-title">{{ __($message) }}</div>
                @if($submessage)
                    <div class="eds-loading-subtitle">{{ __($submessage) }}</div>
                @endif
            </div>
        </div>
    @endif
</div>