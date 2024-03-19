<!-- resources/views/livewire/empleado-form.blade.php -->

<div>
    <form wire:submit.prevent="submitForm" enctype="multipart/form-data">
        <div class="form-group">
            <x-input-validado :readonly="true" label="RFC" placeholder="Ingrese el RFC" wire-model="rfc" wire-attribute="rfc" type="text" />
        </div>
        
        <div class="form-group">
            <x-input-validado label="Nombre" placeholder="Ingrese el nombre" wire-model="nombre" wire-attribute="nombre" type="text" />
        </div>
        
        <div class="form-group">
            <x-input-validado label="Apellido" placeholder="Ingrese el apellido" wire-model="apellido" wire-attribute="apellido" type="text" />
        </div>
        
        <div class="form-group">
            <x-input-validado label="Correo Electrónico" placeholder="Ingrese el correo electrónico" wire-model="email" wire-attribute="email" type="email" />
        </div>
        
        <div class="form-group">
            <x-input-validado label="Teléfono" placeholder="Ingrese el teléfono" wire-model="telefono" wire-attribute="telefono" type="tel" />
        </div>
        
        <div class="form-group">
            <label for="direccion">Dirección</label>
            <textarea class="form-control" id="direccion" placeholder="Ingrese la dirección" wire:model="direccion"></textarea>
        </div>
        
        <div class="form-group">
            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="fecha_nacimiento" wire:model="fecha_nacimiento">
        </div>
        
        <div class="form-group">
            <label for="fecha_inicio">Fecha de Inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" wire:model="fecha_inicio">
        </div>
        
        <div class="form-group">
            <label for="salario">Salario</label>
            <input type="number" class="form-control" id="salario" placeholder="Ingrese el salario" wire:model="salario">
        </div>
        
        <div class="form-group">
            <label for="departamento">Departamento</label>
            <input type="text" class="form-control" id="departamento" placeholder="Ingrese el departamento" wire:model="departamento">
        </div>
        
        <div class="form-group">
            <label for="foto">Foto</label>
            <input type="file" class="form-control-file" id="foto" wire:model="foto" accept="image/*">
            
        </div>
        
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
</div>
