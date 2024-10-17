<?php

namespace App\Livewire\Rh;

use App\Models\CtgDocumentoExpedienteEmpleado;
use App\Models\DocumentoEmpleado;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmpleadosExpedienteDigital extends Component
{
    public $empleadoId;    
    use WithFileUploads;

    public $archivo; // Variable que guarda el archivo subido
    public $tipoDocumentoId; // Variable que almacena el ID del tipo de documento
    public $archivos = [];
    // Reglas de validación para el archivo
    protected $rules = [
        'archivo' => 'required|mimes:jpg,jpeg,png,pdf|max:10240', // Limita tipos y tamaño del archivo (máximo 10 MB)
    ];

    public $isOpen = false;
    public $isOpendos = false;
    public $documentId;


    

    public function openModal($id)
    {
        $this->documentId = $id; // Asigna el ID al atributo
        $this->isOpen = true; // Abre el modal
    }

    public function openModal2($id)
    {
        $this->isOpendos = true; // Abre el modal
    }

    
    public function render()
    {
        $tiposDocumentos = CtgDocumentoExpedienteEmpleado::all();
        $documentosPorEmpleado = DocumentoEmpleado::where('empleado_id',$this->empleadoId)->get();
        return view('livewire.rh.empleados-expediente-digital', compact('tiposDocumentos','documentosPorEmpleado'));
    }

    // Subir archivos y guardar el registro en la base de datos
    public function storeDocuments()
    {
        $this->validate([
            'archivos.*' => 'file|max:2048', // Validar antes de almacenar
            'empleadoId' => 'required|exists:empleados,id',
            'documentId' => 'required|exists:ctg_documentos_expediente_empleados,id',
        ]);

        foreach ($this->archivos as $archivo) {
            // Almacenar el archivo en storage/app/empleadosDoc
            // Obtener el nombre del archivo original
            // Obtener el nombre original sin la extensión
            $filenamedos = pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME);
            // Limpiar el nombre del archivo, eliminando espacios y caracteres especiales
            $cleanFilename = Str::slug($filenamedos, '_');

            // Obtener la extensión del archivo
            $extension = $archivo->getClientOriginalExtension();

            // Buscar si existen otros archivos con el mismo nombre base
            $existingFilesCount = DocumentoEmpleado::where('empleado_id', $this->empleadoId)
                ->where('ctg_documentos_expediente_empleados_id', $this->documentId)
                ->where('nombre_archivo', 'like', '%'.$cleanFilename.'.'.$extension . '%')
                ->count();

            // Crear el nombre del archivo con el contador
            if ($existingFilesCount > 0) {
                $filename = 'empleadoid_'.$this->empleadoId.'_documentoid_'.$this->documentId.'_'.$cleanFilename.'_' . ($existingFilesCount + 1) . '.' . $extension;
            } else {
                $filename = 'empleadoid_'.$this->empleadoId.'_documentoid_'.$this->documentId.'_'.$cleanFilename.'.' . $extension;
            }
                $path = $archivo->storeAs(path: 'empleadosDoc', name: $filename);

            

            // Crear el registro en la base de datos
            DocumentoEmpleado::create([
                'empleado_id' => $this->empleadoId,
                'ctg_documentos_expediente_empleados_id' => $this->documentId,
                'nombre_archivo' => $filename,
                'url_archivo' => $path,  // Guardar la ruta donde se almacenó el archivo
                'status_documento_empleado' => 1, // Puede ser 'pendiente', 'verificado', etc.
            ]);
        }

        // Limpiar la lista de archivos después de guardarlos
        $this->archivos = [];
        $this->dispatch('clear-file-input');
        $this->isOpen = false; // Abre el modal
        session()->flash('message', 'Archivos subidos y almacenados correctamente.');
    }
    

    public function removeFile($index)
    {
        if (isset($this->archivos[$index])) {
            unset($this->archivos[$index]);
            // Reindexar el array para evitar huecos
            $this->archivos = array_values($this->archivos);
        }
    }

    public function descargarDocumento($documentoId)
{
    // Buscar el documento en la base de datos
    $documento = DocumentoEmpleado::find($documentoId);

    if ($documento && Storage::exists($documento->url_archivo)) {
        // Retornar el archivo para su descarga
        return Storage::download($documento->url_archivo, $documento->nombre_archivo);
    } else {
        // Si no existe el archivo, mostrar un mensaje de error
        session()->flash('error', 'El archivo no existe.');
    }
}
public function eliminarDocumento($documentoId)
{
    // Buscar el documento en la base de datos
    $documento = DocumentoEmpleado::find($documentoId);

    if ($documento) {
        // Eliminar el archivo del almacenamiento
        if (Storage::exists($documento->url_archivo)) {
            Storage::delete($documento->url_archivo);
        }

        // Eliminar el registro de la base de datos
        $documento->delete();

        // Mensaje de éxito
        session()->flash('success', 'Documento eliminado exitosamente.');
    } else {
        // Si no se encuentra el documento, mostrar un mensaje de error
        session()->flash('error', 'Documento no encontrado.');
    }
}


}
