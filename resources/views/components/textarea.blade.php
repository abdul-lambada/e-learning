@props(['label' => null, 'name', 'placeholder' => '', 'value' => '', 'rows' => 3])

<div class="mb-3">
    @if ($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif
    <textarea class="form-control @error($name) is-invalid @enderror" id="{{ $name }}" name="{{ $name }}"
        placeholder="{{ $placeholder }}" rows="{{ $rows }}" {{ $attributes }}>{{ old($name, $value) }}</textarea>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
