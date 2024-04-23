<x-form-section submit="updatePassword">
    <x-slot name="title">
        {{ __('Update Password') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </x-slot>

    <x-slot name="form">
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
                    <input id="current_password" type="password" class="form-control" wire:model="state.current_password" autocomplete="current-password">
                    <x-input-error for="current_password" class="mt-2" />
                </div>
            </div>
        
            <div class="col-12">
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('New Password') }}</label>
                    <input id="password" type="password" class="form-control" wire:model="state.password" autocomplete="new-password">
                    <x-input-error for="password" class="mt-2" />
                </div>
            </div>
        
            <div class="col-12">
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                    <input id="password_confirmation" type="password" class="form-control" wire:model="state.password_confirmation" autocomplete="new-password">
                    <x-input-error for="password_confirmation" class="mt-2" />
                </div>
            </div>
        </div>        
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
    </x-slot>
</x-form-section>
