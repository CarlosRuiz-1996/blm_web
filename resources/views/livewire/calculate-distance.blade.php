<div>
    <div>
        <div>
            <label for="origin">Origen:</label>
            <input type="text" id="origin" wire:model="origin" placeholder="Dirección o coordenadas">
        </div>
        <div>
            <label for="destination">Destino:</label>
            <input type="text" id="destination" wire:model="destination" placeholder="Dirección o coordenadas">
        </div>
        <button wire:click="calculate">Calcular Distancia</button>

        @if ($distance && $duration)
        <div>
            <p><strong>Distancia:</strong> {{ $distance }}</p>
            <p><strong>Duración:</strong> {{ $duration }}</p>
        </div>
        @endif
    </div>
</div>