@props([
    'name',
    'label',
    'options' => [],
    'selected' => '',
    'required' => false,
    'placeholder' => '-- Pilih --',
])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label fw-semibold">
        {{ $label }} @if($required)<span class="text-danger">*</span>@endif
    </label>
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        class="form-select @error($name) is-invalid @enderror"
        {{ $required ? 'required' : '' }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $key => $val)
            <option value="{{ $key }}" {{ old($name, $selected) == $key ? 'selected' : '' }}>
                {{ $val }}
            </option>
        @endforeach
    </select>

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
