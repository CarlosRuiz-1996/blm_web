@props(['label'=>null, 'placeholder'=>null, 'wireModel'=>null, 'readonly' => false])

<div class="form-group">
    <label for="{{ $attributes->get('id') }}">{{ $label }}</label>
    <input type="tel" {{-- Cambiado a type="tel" para indicar que es un campo de teléfono --}}
           class="form-control @error($wireModel) is-invalid @enderror"
           id="{{ $attributes->get('id') }}"
           placeholder="{{ $placeholder }}"
           wire:model="{{ $wireModel }}"
           @if($readonly) readonly @endif
           style="text-transform:uppercase;"
           {{-- Añadidas reglas de longitud mínima y máxima --}}
           minlength="8"
           maxlength="10">
    @error($wireModel)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
