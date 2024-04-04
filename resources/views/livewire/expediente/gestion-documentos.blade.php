<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <livewire:cliente-cabecera :cliente="$id" />
                {{-- {{$cliente}} --}}
            </div>
        </div>

        <!--tabla documentacion-->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="text-center">Documentación</h3>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="row">
                                <div class="col-md-4">
                                    <x-select-validado label="Documento:" placeholder="Seleccione tipo de documento"
                                        wire-model="documentoid" required>
                                        @foreach ($documentos as $doc)
                                            <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                                        @endforeach
                                    </x-select-validado>
                                </div>

                                <div class="col-md-4 mt-3">


                                    <!--prueba carga-->
                                    <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                                        x-on:livewire-upload-finish="uploading = false"
                                        x-on:livewire-upload-cancel="uploading = false"
                                        x-on:livewire-upload-error="uploading = false"
                                        x-on:livewire-upload-progress="progress = $event.detail.progress">
                                        <div class="input-group mt-3">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input"
                                                    wire:model="documentoSelec" id="docuemntosselec"
                                                    accept="application/pdf">
                                                <label class="custom-file-label xp" for="docuemntosselecbene"
                                                    aria-describedby="docuemntosselecbene">Seleccione un
                                                    archivo</label>
                                            </div>
                                        </div>
                                        <!-- Progress Bar -->
                                        <div x-show="uploading">
                                            <progress max="100" x-bind:value="progress"></progress>
                                        </div>
                                    </div>
                                    @error('documentoSelec')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 mt-3">
                                    <button wire:click="agregarArchivo" onclick="showProgressModal()"
                                        class="btn btn-primary mt-3">Subir
                                        Archivo</button>
                                </div>

                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="table-info">
                                        <tr>
                                            <th class="col-md-5">Documento</th>
                                            <th class="col-md-4">Archivo Adjunto</th>
                                            <th class="col-md-2 text-center">Estatus</th>
                                            <th class="col-md-1">Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($documentos as $doc)
                                            <tr>
                                                <td class="col-md-5">{{ $doc->name }}</td>
                                                <td class="col-md-4">
                                                    @php $documentoEncontrado = false; @endphp
                                                    @foreach ($documentosexpediente as $documento)
                                                        @if ($documento->ctg_documentos_id == $doc->id)
                                                            <a data-toggle="modal" style="cursor: pointer;"
                                                                data-target="#modalpdf"
                                                                wire:click="openModal('documentos/{{ $rfc }}/{{ $documento->document_name }}')">
                                                                {{ $documento->document_name }}</a>
                                                            @php $documentoEncontrado = true; @endphp
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td class="col-md-2 text-center f-2">
                                                    @if ($documentoEncontrado)
                                                        <i class="fas fa-circle text-success"></i>
                                                    @else
                                                        <i class="fas fa-circle text-danger"></i>
                                                    @endif
                                                </td>
                                                <td class="col-md-1">
                                                    @foreach ($documentosexpediente as $documentoss)
                                                        @if ($documentoss->ctg_documentos_id == $doc->id)
                                                            <button wire:click="eliminarArchivo({{ $documentoss->id }})"
                                                                class="btn btn-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--validacion docuemntos Beneficiarios-->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="text-center">Documentación del beneficiario (OPCIONAL)</h3>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="row">
                                <div class="col-md-2 mt-5">
                                    <label for="checkbene">Habilitar Beneficiario</label>
                                    <input type="checkbox" name="checkbene" id="checkbene"
                                        wire:model.live='checkbeneficiario'>
                                </div>
                                <div class="col-md-4" {{ $habilitados ? 'hidden' : '' }}>
                                    <x-select-validadolive label="Documento:" placeholder="Seleccione tipo de documento"
                                        wire-model="documentoidbene" required>
                                        @foreach ($documentos_beneficiarios as $doc)
                                            <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                                        @endforeach
                                        </x-select-validado>
                                </div>
                                <div class="col-md-4 mt-3" {{ $habilitados ? 'hidden' : '' }}>


                                    <!--prueba carga-->
                                    <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                                        x-on:livewire-upload-finish="uploading = false"
                                        x-on:livewire-upload-cancel="uploading = false"
                                        x-on:livewire-upload-error="uploading = false"
                                        x-on:livewire-upload-progress="progress = $event.detail.progress">
                                        <div class="input-group mt-3">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input"
                                                    wire:model="documentoSelecbene" id="docuemntosselecbene"
                                                    accept="application/pdf">
                                                <label class="custom-file-label xd" for="docuemntosselecbene"
                                                    aria-describedby="docuemntosselecbene">Seleccione un
                                                    archivo</label>
                                            </div>
                                        </div>
                                        <!-- Progress Bar -->
                                        <div x-show="uploading">
                                            <progress max="100" x-bind:value="progress"></progress>
                                        </div>
                                    </div>
                                    @error('documentoSelecbene')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!--dfdsf-->
                                <div class="col-md-2 mt-3" {{ $habilitados ? 'hidden' : '' }}>
                                    <button wire:click="agregarArchivoBeneficiario" class="btn btn-primary mt-3">Subir
                                        Archivo</button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table" {{ $habilitados ? 'hidden' : '' }}>
                                    <thead class="table-info">
                                        <tr>
                                            <th class="col-md-5">Documento</th>
                                            <th class="col-md-4">Archivo Adjunto</th>
                                            <th class="col-md-2 text-center">Estatus</th>
                                            <th class="col-md-1">Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($documentos_beneficiarios as $doca)
                                            <tr>
                                                <td class="col-md-3">
                                                    {{ $doca->name }}
                                                </td>
                                                <td class="col-md-4">
                                                    @php $documentoEncontradobe = false; @endphp
                                                    @foreach ($documentosexpedienteBene as $documentobene)
                                                        @if ($documentobene->ctg_documentos_benf_id == $doca->id)
                                                            <a data-toggle="modal" style="cursor: pointer;"
                                                                data-target="#modalpdf"
                                                                wire:click="openModal('documentos/{{ $rfc }}/beneficiario/{{ $documentobene->document_name }}')">
                                                                {{ $documentobene->document_name }}</a>
                                                            @php $documentoEncontradobe = true; @endphp
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td class="col-md-2 text-center f-2">
                                                    @if ($documentoEncontradobe)
                                                        <i class="fas fa-circle text-success"></i>
                                                    @else
                                                        <i class="fas fa-circle text-danger"></i>
                                                    @endif
                                                </td>
                                                <td class="col-md-1">
                                                    @foreach ($documentosexpedienteBene as $documentobeness)
                                                        @if ($documentobeness->ctg_documentos_benf_id == $doca->id)
                                                            <button
                                                                wire:click="eliminarArchivoBene({{ $documentobeness->id }})"
                                                                class="btn btn-danger"> <i
                                                                    class="fas fa-trash"></i></button>
                                                        @endif
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($cliente_sts == 0)
            <div class="text-center mb-5">
                <button class="btn btn-info btn-block" wire:click="$dispatch('confirm')">Finalizar</button>
            </div>
        @endif
    </div>
    <script>
        document.getElementById('docuemntosselec').addEventListener('change', function() {
            var fileName = this.files[0].name;
            var label = document.querySelector('.xp');
            label.innerText = fileName;
        });
        document.getElementById('docuemntosselecbene').addEventListener('change', function() {
            var fileName = this.files[0].name;
            var label = document.querySelector('.xd');
            label.innerText = fileName;
        });
    </script>
    @once
        @push('js')
            <script>
                document.addEventListener('livewire:initialized', () => {
                    @this.on('confirm', () => {

                        Swal.fire({
                            title: '¿Estas seguro?',
                            text: "El expediente pasara a validación y ya no se podran realizar cambios",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, adelante!',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                @this.dispatch('finalizar-expediente');
                            }
                        })
                    })

                    Livewire.on('agregarArchivocre', function(params) {
                        const nombreArchivo = params[0].nombreArchivo;
                        const tipomensaje = params[1].tipomensaje;
                        Swal.fire({
                            position: 'top-end',
                            icon: tipomensaje,
                            title: nombreArchivo,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });
                    //eliminar alerta
                    Livewire.on('ArchivoEliminado', function(params) {
                        const nombreArchivo = params[0].nombreArchivo;
                        const tipomensaje = params[1].tipomensaje;
                        Swal.fire({
                            position: 'top-end',
                            icon: tipomensaje,
                            title: nombreArchivo,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });

                    Livewire.on('error', function() {

                        Swal.fire({
                            icon: 'error',
                            title: 'Faltan subir documentos',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });
                    Livewire.on('success', function([message]) {
                        var cliente = message[1];
                        Swal.fire({
                            icon: 'success',
                            title: message[0],
                            showConfirmButton: false,
                            timer: 3000,
                        }).then((result) => {

                                if (cliente) {
                                    window.location.href =
                                        '{{ route('cliente.detalles', [':cliente', ':op']) }}'
                                        .replace(':cliente', cliente.id)
                                        .replace(':op', 1);
                                }
                            
                        });
                    });
                });

                function showProgressModal() {

                    var doc = document.getElementById('docuemntosselec');

                    if (doc.value) {
                        let timerInterval;
                        Swal.fire({
                            title: "Subiendo documento!",
                            html: "Esto puede tardar unos segundos.",
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();

                            },

                        });
                    }
                }
            </script>
        @endpush
    @endonce
    <x-modal-pdf title="Documento Cargado" pdfUrl="{{ $pdfUrl }}" wire:ignore.self>
    </x-modal-pdf>


</div>
