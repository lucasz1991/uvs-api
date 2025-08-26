<div class="space-y-4">
    <h2 class="text-lg font-semibold">Aktivitätsprotokoll</h2>

    <ul class="divide-y divide-gray-200 text-sm">
        @forelse ($activities as $activity)
            <li class="py-2">
                <div class="flex justify-between items-center bg-white p-2">
                    <div>
                        <div class="font-semibold text-gray-800">{{ $activity->description }}</div>
                        <div class="text-gray-500 text-xs">
                            {{ $activity->created_at->format('d.m.Y H:i') }}
                            • Event: <span class="font-mono">{{ $activity->event }}</span>
                        </div>
                        @if ($activity->properties)
                            <details class="mt-1 text-xs text-gray-600">
                                <summary class="cursor-pointer">Details</summary>
                                <pre class="bg-gray-50 p-2 rounded mt-1">{{ json_encode($activity->properties->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                            </details>
                        @endif
                    </div>
                </div>
            </li>
        @empty
            <li class="py-4 text-gray-500">Keine Aktivitäten gefunden.</li>
        @endforelse
    </ul>

    <div>
        {{ $activities->links() }}
    </div>
</div>
