@props(['placeholder', 'required'])

<div class="form-group">
    <label for="{{ $attributes->get('id') }}">{{ $slot }}</label>
    <input type="text" class="form-control @error($attributes->get('wireModel')) is-invalid @enderror"
           id="{{ $attributes->get('id') }}" placeholder="{{ $placeholder }}"  @if($required) required @endif>
    @error($attributes->get('wireModel'))
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
