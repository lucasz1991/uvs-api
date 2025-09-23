{{-- resources/views/livewire/admin/logs/viewer.blade.php --}}
<div class="p-6 space-y-6" wire:poll.20s>
    <div class="flex flex-wrap items-center gap-3">
        <h1 class="text-xl font-semibold">System Logs</h1>

        <select wire:model.live="currentFile" class="border rounded px-2 py-1">
            @foreach($files as $f)
                <option value="{{ $f['name'] }}">
                    {{ $f['name'] }} — {{ number_format($f['size']/1024,1) }} KB
                </option>
            @endforeach
        </select>

        <input type="text" wire:model.live.debounce.300ms="search"
               placeholder="Suche in Zeilen…"
               class="border rounded px-2 py-1 w-64"/>

        @if($fileMeta)
            <button wire:click="download('{{ $fileMeta['name'] }}')" class="px-3 py-1 border rounded">
                Download
            </button>
            <button wire:click="deleteFile('{{ $fileMeta['name'] }}')" class="px-3 py-1 border rounded text-red-600"
                    onclick="return confirm('Diese Logdatei wirklich löschen?')">
                Löschen
            </button>
            <span class="text-sm text-gray-500">
                {{ $total }} Zeilen (zeige letzte {{ $lines }})
            </span>
        @endif
    </div>

    <div class=" text-sm overflow-auto max-h-[70vh]">
        @forelse($items as $row)
            <div class="px-3 py-1  border-gray-800 border rounded bg-blue-100 text-gray-700 mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-gray-400">{{ $row['time'] ?? '' }}</span>
                    @if($row['level'])
                        <span @class([
                            'text-xs px-2 py-0.5 rounded',
                            'bg-red-700' => in_array($row['level'], ['emergency','alert','critical','error']),
                            'bg-yellow-700' => in_array($row['level'], ['warning','notice']),
                            'bg-blue-700' => in_array($row['level'], ['info']),
                            'bg-gray-700' => in_array($row['level'], ['debug']),
                        ])>
                            {{ strtoupper($row['level']) }}
                        </span>
                    @endif
                    @if($row['env'])
                        <span class="text-xs px-2 py-0.5 rounded bg-gray-700">{{ $row['env'] }}</span>
                    @endif
                </div>
                <div class="mt-1 whitespace-pre-wrap leading-snug">{{ $row['message'] }}</div>
                @if($row['context'])
                    <details class="mt-1">
                        <summary class="text-xs text-gray-400 cursor-pointer">Context</summary>
                        <pre class="mt-1 whitespace-pre-wrap">{{ $row['context'] }}</pre>
                    </details>
                @endif
            </div>
        @empty
            <div class="p-4 text-gray-400">Keine Zeilen gefunden.</div>
        @endforelse
    </div>

    {{-- Simple Pager --}}
    @if($pages > 1)
        <div class="flex gap-2 items-center">
            @for($i=1; $i<=$pages; $i++)
                <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}"
                   class="px-2 py-1 border rounded {{ $i==$page ? 'bg-gray-200' : '' }}">
                    {{ $i }}
                </a>
            @endfor
        </div>
    @endif
</div>
