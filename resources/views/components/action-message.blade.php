@props(['on'])

<div x-data="{ shown: false, timeout: null }"
    x-init="@this.on('{{ $on }}', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000); })"
    x-show="shown"
    x-transition:leave.opacity.duration.1500ms
    class="alert alert-success text-sm text-gray-600"
    style="display: none;">
    {{ $slot->isEmpty() ? 'Saved.' : $slot }}
</div>
