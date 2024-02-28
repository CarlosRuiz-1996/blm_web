@props(['label','placeholder', 'wireModel',  'wireAttribute', 'type' => 'text'])

<div class="form-group">
    <label for="{{ $attributes->get('id') }}">{{ $label }}</label>
    <input type="{{ $type }}" class="form-control @error($wireAttribute) is-invalid @enderror"
           id="{{ $attributes->get('id') }}" placeholder="{{ $placeholder }}" wire:model="{{ $wireModel }}" 
           style="text-transform:uppercase;">
    @error($wireAttribute)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
