@props(['label' => null, 'name', 'options' => [], 'selected' => null])

<div class="mb-3">
    @if ($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif
    <select class="form-select @error($name) is-invalid @enderror" id="{{ $name }}" name="{{ $name }}"
        {{ $attributes }}>
        {{ $slot }}
    </select>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
