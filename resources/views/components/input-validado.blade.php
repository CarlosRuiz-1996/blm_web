@props(['label','placeholder', 'wireModel', 'required'])

<div class="form-group">
    <label for="{{ $attributes->get('id') }}">{{ $label  }}</label>
    <input type="text" class="form-control @error($attributes->get('wireModel')) is-invalid @enderror"
           id="{{ $attributes->get('id') }}" placeholder="{{ $placeholder }}"  @if($required) required @endif
           style="text-transform:uppercase;" 
           >
    @error($attributes->get('wireModel'))
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
