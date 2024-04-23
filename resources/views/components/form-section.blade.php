@props(['submit'])

<div {{ $attributes->merge(['class' => 'container-fluid']) }}>
    <div class="row">
        <div class="col-md-12">
            <x-section-title>
                <x-slot name="title">{{ $title }}</x-slot>
                <x-slot name="description">{{ $description }}</x-slot>
            </x-section-title>

            <div class="mt-5">
                <form wire:submit="{{ $submit }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                {{ $form }}
                            </div>
                            @if (isset($actions))
                                <div class="mt-3">
                                    {{ $actions }}
                                </div>
                            @endif
                        </div>
                    </div>

                    
                </form>
            </div>
        </div>
    </div>
</div>
