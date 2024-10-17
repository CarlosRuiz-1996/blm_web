@props(['label', 'placeholder' => null, 'wireModel', 'required' => false])

<div class="form-group">
    <label for="{{ $attributes->get('id') }}">{{ $label }}</label>
    <select class="form-control @error($wireModel) is-invalid @enderror" id="{{ $attributes->get('id') }}" wire:model="{{ $wireModel }}" {{ $required ? 'required' : '' }}>
        <option value="" selected>Seleccione</option>
        {{ $slot }}
    </select>
    @error($wireModel)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
