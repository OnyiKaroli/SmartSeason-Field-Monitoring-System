<x-app-layout>
    <x-slot name="title">Admin Dashboard - {{ config('app.name') }}</x-slot>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Admin Dashboard</h1>
                <p class="text-sm text-gray-500 mt-0.5">Overview of all fields and agent activity</p>
            </div>
        </div>
    </x-slot>

    {{-- Phase 7 will replace this placeholder with full dashboard metrics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <div class="bg-white rounded-xl border border-gray-200 p-6 flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0">
                <span class="text-emerald-600 text-lg font-bold">{{ $stats['total_fields'] }}</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Fields</p>
                <p class="text-2xl font-semibold text-gray-800 mt-1">{{ $stats['total_fields'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                <span class="text-blue-600 text-lg font-bold">{{ $stats['active_fields'] }}</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Active Fields</p>
                <p class="text-2xl font-semibold text-gray-800 mt-1">{{ $stats['active_fields'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                <span class="text-amber-600 text-lg font-bold">{{ $stats['at_risk'] }}</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">At Risk</p>
                <p class="text-2xl font-semibold text-gray-800 mt-1">{{ $stats['at_risk'] }}</p>
            </div>
        </div>

    </div>

    <div class="mt-8 bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Recent Field Updates</h3>
            <a href="{{ route('fields.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">View All Fields &rarr;</a>
        </div>
        
        @if ($recentUpdates->isEmpty())
            <div class="p-6 text-center">
                <p class="text-sm text-gray-500 py-6">
                    No field updates recorded yet.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Update</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($recentUpdates as $update)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <a href="{{ route('fields.show', $update->field) }}" class="hover:text-indigo-600">
                                        {{ $update->field->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $update->updater->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Stage: <span class="font-medium text-gray-900">{{ $update->new_stage }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                    {{ $update->observed_at->diffForHumans() }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</x-app-layout>
