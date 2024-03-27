<!-- habilitar-deshabilitar-input.blade.php -->

@props(['label' => null, 'placeholder' => null, 'wireModel' => null, 'readonly' => false, 'type' => 'text', 'withCheckbox' => false])

<div class="form-group">
    <label for="{{ $attributes->get('id') }}">{{ $label }}</label>
    
    @if($withCheckbox)
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="{{ $attributes->get('id') }}_checkbox" wire:model="{{ $wireModel }}_checkbox">
        <label class="form-check-label" for="{{ $attributes->get('id') }}_checkbox">Habilitar</label>
    </div>
    @endif
    
    <input type="{{ $type }}"
           class="form-control @if($withCheckbox && !$attributes->get('wire:model')->contains($wireModel.'_checkbox')) disabled @endif @error($wireModel) is-invalid @enderror"
           id="{{ $attributes->get('id') }}"
           placeholder="{{ $placeholder }}"
           wire:model="{{ $wireModel }}"
           @if($readonly || ($withCheckbox && !$attributes->get('wire:model')->contains($wireModel.'_checkbox'))) disabled @endif
           style="text-transform:uppercase;">
           
    @error($wireModel)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
