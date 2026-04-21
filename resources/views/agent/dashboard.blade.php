<x-app-layout>
    <x-slot name="title">My Fields — {{ config('app.name') }}</x-slot>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">My Fields</h1>
                <p class="text-sm text-gray-500 mt-0.5">Fields assigned to you this season</p>
            </div>
        </div>
    </x-slot>

    {{-- Phase 7 will replace this with agent-scoped field summaries --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

        @foreach ([['Assigned Fields', '—', 'blue'], ['Needing Update', '—', 'amber']] as [$label, $value, $color])
            <div class="bg-white rounded-xl border border-gray-200 p-6 flex items-start gap-4">
                <div class="w-10 h-10 rounded-lg bg-{{ $color }}-100 flex items-center justify-center shrink-0">
                    <span class="text-{{ $color }}-600 text-lg font-bold">{{ $value }}</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
                    <p class="text-2xl font-semibold text-gray-800 mt-1">{{ $value }}</p>
                </div>
            </div>
        @endforeach

    </div>

    <div class="mt-8 bg-white rounded-xl border border-gray-200 p-6">
        <p class="text-sm text-gray-500 text-center py-6">
            Your assigned fields will appear here once the field management module is complete (Phase 3+).
        </p>
    </div>

</x-app-layout>
