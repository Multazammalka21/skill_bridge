@if($href)
    <a href="{{ $href }}" class="pinteria-btn pinteria-btn--{{ $variant }} {{ $disabled ? 'pinteria-btn--disabled' : '' }}" {{ $attributes }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" class="pinteria-btn pinteria-btn--{{ $variant }} {{ $disabled ? 'pinteria-btn--disabled' : '' }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes }}>
        {{ $slot }}
    </button>
@endif
