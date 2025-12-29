@props(['label' => null, 'name', 'type' => 'text', 'placeholder' => '', 'value' => ''])

<div class="mb-3">
    @if ($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif
    <input type="{{ $type }}" class="form-control @error($name) is-invalid @enderror" id="{{ $name }}"
        name="{{ $name }}" placeholder="{{ $placeholder }}" value="{{ old($name, $value) }}" {{ $attributes }}>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
