<x-app-layout>
    <x-slot name="title">My Fields - {{ config('app.name') }}</x-slot>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">My Fields</h1>
                <p class="text-sm text-gray-500 mt-0.5">Fields assigned to you this season</p>
            </div>
        </div>
    </x-slot>

    {{-- Phase 7 will replace this with agent-scoped field summaries --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <div class="bg-white rounded-xl border border-gray-200 p-6 flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                <span class="text-blue-600 text-lg font-bold">{{ $stats['total_assigned'] }}</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Assigned Fields</p>
                <p class="text-2xl font-semibold text-gray-800 mt-1">{{ $stats['total_assigned'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                <span class="text-green-600 text-lg font-bold">{{ $stats['active'] }}</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Active Fields</p>
                <p class="text-2xl font-semibold text-gray-800 mt-1">{{ $stats['active'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center shrink-0">
                <span class="text-red-600 text-lg font-bold">{{ $stats['at_risk'] }}</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">At Risk</p>
                <p class="text-2xl font-semibold text-gray-800 mt-1">{{ $stats['at_risk'] }}</p>
            </div>
        </div>

    </div>

    <div class="mt-8 bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Your Fields</h3>
        </div>
        
        @if ($fields->isEmpty())
            <div class="p-6 text-center">
                <p class="text-sm text-gray-500 py-6">
                    You don't have any fields assigned to you yet.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Crop</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stage</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($fields as $field)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $field->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $field->crop_type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $field->current_stage === 'Planted' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $field->current_stage === 'Growing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $field->current_stage === 'Ready' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $field->current_stage === 'Harvested' ? 'bg-gray-100 text-gray-800' : '' }}
                                    ">
                                        {{ $field->current_stage }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $field->status === 'Active' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $field->status === 'At Risk' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $field->status === 'Completed' ? 'bg-blue-100 text-blue-800' : '' }}
                                    ">
                                        {{ $field->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('fields.show', $field) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                        View & Update
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</x-app-layout>
