@php
    $icons = [
        'success' => '✅',
        'error'   => '❌',
        'warning' => '⚠️',
        'info'    => 'ℹ️',
    ];
@endphp

<div class="pinteria-alert pinteria-alert--{{ $type }}" role="alert" {{ $attributes }}>
    <span class="pinteria-alert__icon">{{ $icons[$type] ?? 'ℹ️' }}</span>
    <div class="pinteria-alert__content">{{ $slot }}</div>
    @if($dismissible)
        <button type="button" class="pinteria-alert__close" onclick="this.parentElement.remove()" aria-label="Tutup">&times;</button>
    @endif
</div>
