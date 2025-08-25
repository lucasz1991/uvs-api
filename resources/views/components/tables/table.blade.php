@props([
    'columns' => [],
    'items' => [],
    'empty' => 'Keine Einträge gefunden.',
    'class' => '',
    // Sort-Status (optional, nur für Pfeil & dir-Berechnung)
    'sortBy' => null,        // z.B. 'title'
    'sortDir' => 'asc',      // 'asc' | 'desc'
])

@php
    // Normalisiere Columns
    $columns = collect($columns)->map(function ($c) {
        if (is_string($c)) {
            return [
                'label'    => $c,
                'key'      => \Illuminate\Support\Str::slug($c, '_'),
                'width'    => '1fr',
                'sortable' => false,
                'hideOn'   => 'none',
            ];
        }
        $label    = $c['label'] ?? '';
        $key      = $c['key']   ?? $label;
        $width    = $c['width'] ?? '1fr';
        $sortable = (bool)($c['sortable'] ?? false);
        $hideOn   = $c['hideOn'] ?? 'none';

        return compact('label','key','width','sortable','hideOn');
    });

    // Grid-Template nur für md+ (mobil ist gestackt)
    $gridTemplate = $columns->map(fn($c) => $c['width'])->implode(' ');

    // Mapping für hideOn -> Utility-Klassen
    $hideClass = function (string $hideOn) {
        return match ($hideOn) {
            'sm'  => 'hidden sm:block',
            'md'  => 'hidden md:block',
            'lg'  => 'hidden lg:block',
            'xl'  => 'hidden xl:block',
            default => '', // 'none'
        };
    };

    // Pfeil für Sort-Indikator
    $arrowFor = function ($colKey, $sortBy, $sortDir) {
        if ($sortBy !== $colKey) return '';
        return $sortDir === 'asc' ? '▲' : '▼';
    };
@endphp

<div {{ $attributes->merge(['class' => 'w-full mt-4 relative '.$class]) }}>
    {{-- Header (nur md+) --}}
    <div class="hidden md:grid bg-gray-100 p-2 font-semibold text-gray-700 border-b text-left text-sm"
         style="grid-template-columns: {{ $gridTemplate }};">
        @foreach($columns as $col)
            @php $hidden = $hideClass($col['hideOn']); @endphp

            @if($col['sortable'])
                <button
                    type="button"
                    class="px-2 py-2 text-left flex items-center gap-1 {{ $hidden }}"
                    @click="$dispatch('table-sort', {
                        key: '{{ $col['key'] }}',
                        dir: '{{ ($sortBy == $col['key'] && $sortDir == 'asc') ? 'desc' : 'asc' }}'
                    })"
                >
                    <span>{{ $col['label'] }}</span>
                        <span class="text-[10px] opacity-70">
                            {{ $arrowFor($col['key'], $sortBy, $sortDir) }}
                        </span>
                </button>
            @else
                <div class="px-2 py-2 {{ $hidden }}">{{ $col['label'] }}</div>
            @endif
        @endforeach
    </div>

    {{-- Rows --}}
    @forelse($items as $item)
    <div class="relative border-b py-2 text-sm md:px-2">
        <div class="grid items-center" style="grid-template-columns: {{ $gridTemplate }} min-content;">
        {{-- Zellen --}}
        @if($rowView)
            @include($rowView, ['item' => $item, 'columnsMeta' => $columns, 'hideClass' => $hideClass])
        @else
            @foreach($columns as $col)
            <div class="px-2 py-2 {{ $hideClass($col['hideOn']) }}">—</div>
            @endforeach
        @endif

        {{-- Actions rechts, ohne absolute --}}
        @if($actionsView)
            <div class="justify-self-end relative right-5">
            @include($actionsView, ['item' => $item])
            </div>
        @endif
        </div>
    </div>
    @empty
    <div class="p-4 text-sm text-gray-500">{{ $empty }}</div>
    @endforelse

</div>
