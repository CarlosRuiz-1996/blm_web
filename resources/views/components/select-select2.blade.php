@props(['label', 'placeholder' => null, 'wireModel', 'required', 'classSelect2' => null, 'modalName' => null])

<div class="form-group">
    <label for="{{ $attributes->get('id') }}">{{ $label }}</label>
    <br>
    <select class="@error($wireModel) is-invalid @enderror {{ $classSelect2 }}" name="state"
        id="{{ $attributes->get('id') }}" wire:model.live="{{ $wireModel }}"
        @if ($required) required @endif>
        <option value="" selected>Seleccione</option>
        {{ $slot }}
    </select>
    @error($wireModel)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
@push('js')
    <script>
        $(document).ready(function() {
            var modalElement = $('#{{ $modalName }}');
            var selectElement = $('.{{ $classSelect2 }}');



            if (modalElement.length) {
                selectElement.select2({
                    dropdownParent: modalElement,
                    width: '100%',
                });
            } else {
                selectElement.select2({
                    width: '100%',
                });
            }
            selectElement.on('change', function() {
                var value = $(this).val();
                @this.set('{{ $wireModel }}', value);
            });
        });
    </script>
@endpush
<style>
    .select2-container .select2-selection--single {
        height: 40px;
        /* Ajusta este valor a la altura deseada */
        display: flex;
        align-items: center;
    }

    .select2-container .select2-selection__rendered {
        line-height: 40px;
        /* Asegúrate de que coincida con la altura */
    }

    .select2-container .select2-selection__arrow {
        height: 40px;
        /* Ajusta también la altura de la flecha */
    }
</style>
