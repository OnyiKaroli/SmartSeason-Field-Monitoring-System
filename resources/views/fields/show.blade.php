<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Field Details') }}: {{ $field->name }}
            </h2>
            <div>
                <a href="{{ route('fields.edit', $field) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                    Edit
                </a>
                <form action="{{ route('fields.destroy', $field) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this field?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Field Information</h3>
                            
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $field->name }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Crop Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $field->crop_type }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Planting Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $field->planting_date->format('Y-m-d') }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Current Stage</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $field->current_stage === 'Planted' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $field->current_stage === 'Growing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $field->current_stage === 'Ready' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $field->current_stage === 'Harvested' ? 'bg-gray-100 text-gray-800' : '' }}
                                        ">
                                            {{ $field->current_stage }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Meta Information</h3>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Assigned Agent</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $field->assignedAgent ? $field->assignedAgent->name : 'Unassigned' }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Created By</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $field->creator ? $field->creator->name : 'Unknown' }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Created At</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $field->created_at->format('Y-m-d H:i') }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $field->updated_at->format('Y-m-d H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-4 border-t border-gray-200">
                        <a href="{{ route('fields.index') }}" class="text-indigo-600 hover:text-indigo-900">
                            &larr; Back to Fields
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
