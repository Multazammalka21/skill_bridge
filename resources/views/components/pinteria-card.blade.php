<div class="pinteria-card pinteria-card--{{ $variant }}">
    @if($title || $icon)
        <div class="pinteria-card__header">
            @if($icon)<span class="pinteria-card__icon">{{ $icon }}</span>@endif
            @if($title)<h3 class="pinteria-card__title">{{ $title }}</h3>@endif
        </div>
    @endif
    <div class="pinteria-card__body">
        {{ $slot }}
    </div>
</div>
