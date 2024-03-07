
@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
        class="alert alert-success"
        role="alert"
        style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999;">

        <strong class="font-bold">¡Éxito!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
        class="alert alert-danger"
        role="alert">
        <strong class="font-bold">¡Error!</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif
