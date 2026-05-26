@props([
    'label' => null,
    'name' => null,
    'required' => false,
    'hint' => null,
    'for' => null,
    'error' => null,
    'wide' => false,
])

@php
    $fieldFor = $for ?? $name;
    $errorBag = $error ?? ($name && $errors ? $errors->first($name) : null);
    $wrapperClass = 'vx-form-group' . ($wide ? ' vx-form-full' : '');
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <label class="vx-label" @if($fieldFor) for="{{ $fieldFor }}" @endif>
            {{ $label }}
            @if($required)<span class="required">*</span>@endif
        </label>
    @endif

    {{ $slot }}

    @if($errorBag)
        <div class="vx-invalid-feedback">{{ $errorBag }}</div>
    @endif
    @if($hint)
        <div class="vx-form-hint">{{ $hint }}</div>
    @endif
</div>
