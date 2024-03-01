@props(['label','placeholder', 'wireModel',  'type' => 'text'])

<div class="form-group">
    <label for="{{ $attributes->get('id') }}">{{ $label }}</label>
    <input type="{{ $type }}" class="form-control @error($wireModel) is-invalid @enderror"
           id="{{ $attributes->get('id') }}" placeholder="{{ $placeholder }}" wire:model="{{ $wireModel }}" 
           style="text-transform:uppercase;">
   
    @error($wireModel)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>