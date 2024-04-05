{{-- @props(['label'=>null,  'placeholder'=>null, 'wireModel'=>null, 'readonly' => false, 'type' => 'text',])

<div class="form-group">
    <label for="{{ $attributes->get('id') }}">{{ $label }}</label>
    <input type="{{ $type }}"
           class="form-control @error($wireModel) is-invalid @enderror"
           id="{{ $attributes->get('id') }}"
           placeholder="{{ $placeholder }}"
           wire:model="{{ $wireModel }}"
           @if($readonly) readonly @endif
           style="text-transform:uppercase;">
    @error($wireModel)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div> --}}


@props([
    'label' => null,
    'placeholder' => null,
    'wireModel' => null,
    'readonly' => false,
    'type' => 'text',
    'alpine' => [] // Añade esta línea para aceptar atributos de Alpine.js
])

<div class="form-group">
    <label for="{{ $attributes->get('id') }}">{{ $label }}</label>
    <input type="{{ $type }}"
           {{ $attributes->merge($alpine) }} 
           class="form-control @error($wireModel) is-invalid @enderror"
           id="{{ $attributes->get('id') }}"
           placeholder="{{ $placeholder }}"
           wire:model="{{ $wireModel }}"
           @if($readonly) readonly @endif
           style="text-transform:uppercase;">
    @error($wireModel)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
