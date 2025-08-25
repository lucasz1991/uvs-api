<div class="px-2">
    <div class="flex justify-between mb-4">
        <x-slot name="header">
            <x-slot name="title">Kursliste</x-slot>
        </x-slot>
        <div class="flex items-center space-x-2">
            <h1 class="flex items-center text-lg font-semibold px-2 py-1">
                <span>Kurse</span>
                <span class="ml-2 bg-white text-sky-600 text-xs shadow border border-sky-200 font-bold px-2 py-1 flex items-center justify-center rounded-full h-7 leading-none">
                    {{ $coursesTotal }}
                </span>
            </h1>
            <x-tables.search-field 
                resultsCount="{{ $courses->count() }}"
                wire:model.live="search"
            />
        </div>
        <x-link-button @click="$dispatch('open-course-create-edit')" class="btn-xs py-0 leading-[0]">+</x-link-button>
    </div>

    <div class="w-full">
        <x-tables.table
            :columns="[
                ['label'=>'Titel','key'=>'title','width'=>'25%','sortable'=>true,'hideOn'=>'none'],
                ['label'=>'Tutor','key'=>'users.name','width'=>'20%','sortable'=>true,'hideOn'=>'md'],
                ['label'=>'Zeitraum','key'=>'start_time','width'=>'20%','sortable'=>true,'hideOn'=>'xl'],
                ['label'=>'Status','key'=>'status','width'=>'20%','sortable'=>false,'hideOn'=>'md'],
                ['label'=>'Aktivitäten','key'=>'activity','width'=>'15%','sortable'=>true,'hideOn'=>'md'],
            ]"
            :items="$courses"
            row-view="components.tables.rows.courses.course-row"
            actions-view="components.tables.rows.courses.course-actions"
            :sort-by="$sortBy ?? null"
            :sort-dir="$sortDir ?? 'asc'"
        />
        <div class="py-4">
            {{ $courses->links() }}
        </div>
    @livewire('admin.courses.course-create-edit')
</div>
