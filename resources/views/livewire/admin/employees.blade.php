<div>
    <h1 class="text-2xl font-bold mb-4">Mitarbeiter</h1>

    <!-- Tabellenkopf -->
    <div class="grid grid-cols-12 bg-gray-100 p-4 font-semibold text-gray-700 border-b border-gray-300">
        <div class="col-span-1 truncate">#</div>
        <div class="col-span-4 truncate">Name</div>
        <div class="col-span-4 truncate">E-Mail</div>
        <div class="col-span-2 truncate">Erstellt am</div>
        <div class="col-span-1 truncate">Aktion</div>
    </div>

    <!-- Tabelleninhalt -->
    <div class="divide-y divide-gray-200">
        @forelse ($employees as $employee)
            <div class="grid grid-cols-12 p-4 items-center">
                <!-- Mitarbeiter-ID -->
                <div class="col-span-1 truncate">{{ $employee->id }}</div>

                <!-- Name -->
                <div class="col-span-4 truncate">{{ $employee->name }}</div>

                <!-- E-Mail -->
                <div class="col-span-4 truncate">{{ $employee->email }}</div>

                <!-- Erstellungsdatum -->
                <div class="col-span-2 truncate">{{ \Carbon\Carbon::parse($employee->created_at)->format('d.m.Y') }}</div>

                <!-- Aktion -->
                <div class="col-span-1 truncate">
                    <a href="" class="text-blue-500 hover:underline">Details</a>
                </div>
            </div>
        @empty
            <div class="p-4 text-center text-gray-500">
                Keine Mitarbeiter gefunden.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $employees->links('vendor.pagination.tailwind') }}
    </div>
</div>
