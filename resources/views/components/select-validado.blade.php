@props(['label','placeholder'=>null, 'wireModel', 'required'])

<div class="form-group">
    <label for="{{ $attributes->get('id') }}">{{ $label }}</label>
    <select class="form-control @error($wireModel) is-invalid @enderror" id="{{ $attributes->get('id') }}" wire:model="{{ $wireModel }}" @if($required) required @endif>
        {{-- <option value="0" disabled selected>{{ $placeholder }}</option> --}}
        {{ $slot }}
    </select>
    @error($wireModel)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
