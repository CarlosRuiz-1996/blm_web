@props(['label'=>null, 'placeholder'=>null, 'wireModel'=>null, 'readonly' => false, 'type' => 'text'])

<div class="form-group">
    <label for="{{ $attributes->get('id') }}">{{ $label }}</label>
    <input type="{{ $type }}" min="<?= date("Y-m-d") ?>"
           class="form-control @error($wireModel) is-invalid @enderror"
           id="{{ $attributes->get('id') }}"
           placeholder="{{ $placeholder }}"
           wire:model.live="{{ $wireModel }}" 
           @if($readonly) readonly @endif
           style="text-transform:uppercase;">
    @error($wireModel)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>