@props(['title' => null, 'headerAction' => null, 'top' => null, 'footer' => null])

<div {{ $attributes->merge(['class' => 'card mb-4']) }}>
    @if ($title || $headerAction)
        <div class="card-header d-flex justify-content-between align-items-center">
            @if ($title)
                <h5 class="mb-0">{{ $title }}</h5>
            @endif
            @if ($headerAction)
                <div>{{ $headerAction }}</div>
            @endif
        </div>
    @endif

    {{ $top ?? '' }}

    <div class="card-body">
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="card-footer py-3">
            {{ $footer }}
        </div>
    @endif
</div>
