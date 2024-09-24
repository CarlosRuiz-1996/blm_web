@props([
    'label' => null,
    'placeholder' => null,
    'wireModel' => null,
    'readonly' => false,
    'type' => 'text',
    'disabled' => false, // Aceptar el estado de deshabilitado
    'alpine' => [] // Aceptar atributos de Alpine.js
])

<div class="form-group">
    <label for="{{ $attributes->get('id') }}">{{ $label }}</label>
    <input type="{{ $type }}"
           {{ $attributes->merge($alpine) }} 
           class="form-control @error($wireModel) is-invalid @enderror"
           id="{{ $attributes->get('id') }}"
           placeholder="{{ $placeholder }}"
           wire:model.live="{{ $wireModel }}"
           @if($readonly) readonly @endif
           @if($disabled) disabled @endif
           style="text-transform:uppercase;">
    @error($wireModel)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
