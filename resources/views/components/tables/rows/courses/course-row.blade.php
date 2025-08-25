@php
    // Kurzhelfer pro Spaltenindex
    $hc = fn($i) => $hideClass($columnsMeta[$i]['hideOn'] ?? 'none');
@endphp

<div class="px-2 py-2 flex items-center space-x-2 {{ $hc(0) }}">
    <div class="font-semibold truncate">{{ $item->title }}</div>
    @if($item->archived)
        <span class="px-2 py-1 text-xs text-red-700 bg-red-100 rounded">Archiviert</span>
    @endif
</div>

<div class="px-2 py-0 text-gray-700 truncate {{ $hc(1) }}">
    <x-user.public-info :user="$item->tutor" />
</div>

<div class="px-2 py-2 text-xs text-gray-600 {{ $hc(2) }}">
    <span class="text-green-700">
        {{ $item->start_time ? $item->start_time->locale('de')->isoFormat('ll') : '–' }}
    </span>
    <span>–</span>
    <span class="text-red-700">
        {{ $item->end_time ? $item->end_time->locale('de')->isoFormat('ll') : '—' }}
    </span>
</div>

<div class="px-2 py-2 {{ $hc(3) }}">
    @if ($item->status === 'draft')
        <span class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded">Entwurf</span>
    @elseif ($item->status === 'active')
        <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-50 rounded">Aktiv</span>
    @elseif ($item->status === 'archived')
        <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-50 rounded">Archiviert</span>
    @else
        <span class="text-xs text-gray-400">—</span>
    @endif
</div>

<div class="px-2 py-2 text-xs text-gray-500 {{ $hc(4) }}">
    {{ $item->updated_at?->locale('de')->diffForHumans() }}
</div>
