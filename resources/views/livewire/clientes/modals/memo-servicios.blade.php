<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    {{-- elegir sucursal --}}
    <div class="modal fade" wire:ignore.self id="modalMemo" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="exampleModalLabel">Complementos del servicio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-3 mb-3">
                            <x-select-validadolive label="HORARIO DE ENTREGA:" placeholder="Selecciona"
                                wire-model="form.horarioEntrega" required>

                                @foreach ($ctg_horario_entrega as $ctg)
                                    <option value="{{ $ctg->id }}">{{ $ctg->name }}</option>
                                @endforeach

                            </x-select-validadolive>

                        </div>
                        <div class="col-md-4 mb-3">
                            <x-select-validadolive label="DIA DE ENTREGA:" placeholder="Selecciona"
                                wire-model="form.diaEntrega" required>
                                @foreach ($ctg_dia_entrega as $ctg)
                                    <option value="{{ $ctg->id }}">{{ $ctg->name }}
                                    </option>
                                @endforeach

                            </x-select-validadolive>
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-select-validadolive label="HORARIO DE SERVICIO:" placeholder="Selecciona"
                                wire-model="form.horarioServicio" required>
                                @foreach ($ctg_horario_servicio as $ctg)
                                    <option value="{{ $ctg->id }}">{{ $ctg->name }}
                                    </option>
                                @endforeach

                            </x-select-validadolive>
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-select-validadolive label="DIA DE SERVICIO:" placeholder="Selecciona"
                                wire-model="form.diaServicio" required>
                                @foreach ($ctg_dia_servicio as $ctg)
                                    <option value="{{ $ctg->id }}">{{ $ctg->name }}
                                    </option>
                                @endforeach

                            </x-select-validadolive>
                        </div>
                        <div class="col-md-4 mb-3">
                            <x-select-validadolive label="CONSIGNATARIO:" placeholder="Selecciona"
                                wire-model="form.consignatorio" required>
                                @foreach ($ctg_consignatario as $ctg)
                                    <option value="{{ $ctg->id }}">{{ $ctg->name }}
                                    </option>
                                @endforeach

                            </x-select-validadolive>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" wire:click='terminar()'>Terminar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {

                @this.on('open-memo', () => {

                    $('#modalMemo').modal('show');
                })


            });
        </script>
    @endpush
</div>
