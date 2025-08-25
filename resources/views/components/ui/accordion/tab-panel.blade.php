@props([
    'for' => null, // Tab-Key, z. B. 'basic'
    'panelClass' => 'space-y-4 bg-gray-100 p-4 rounded-b-lg rounded-se-lg border border-gray-300 z-10',
])

<div
    x-show="openTab === '{{ $for }}'"
    x-cloak
    role="tabpanel"
    :aria-hidden="openTab !== '{{ $for }}'"
    class="{{ $panelClass }}"
>
    {{ $slot }}
</div>
