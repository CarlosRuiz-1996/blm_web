@props(['label', 'wireModel'])

    <div class="form-group">
        <div class="custom-control custom-switch custom-switch-xl">
            <input type="checkbox" class="custom-control-input" id="{{ $attributes->get('id') }}" wire:model="{{ $wireModel }}" name="{{ $wireModel }}">
            <label class="custom-control-label" for="{{ $attributes->get('id') }}">{{ $label }}</label>
        </div>
    </div>
