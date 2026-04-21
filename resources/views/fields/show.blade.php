<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Field Details') }}: {{ $field->name }}
            </h2>
            @can('update', $field)
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
            @endcan
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

            @can('updateStatus', $field)
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Submit Stage Update</h3>
                    <form action="{{ route('fields.updates.store', $field) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="new_stage" :value="__('New Stage')" />
                                <select id="new_stage" name="new_stage" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach(\App\Models\Field::STAGES as $stage)
                                        <option value="{{ $stage }}" {{ $field->current_stage === $stage ? 'selected' : '' }}>
                                            {{ $stage }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('new_stage')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="observed_at" :value="__('Observation Date')" />
                                <x-text-input id="observed_at" class="block mt-1 w-full" type="datetime-local" name="observed_at" :value="old('observed_at', now()->format('Y-m-d\TH:i'))" required />
                                <x-input-error :messages="$errors->get('observed_at')" class="mt-2" />
                            </div>
                            <div class="md:col-span-3">
                                <x-input-label for="note" :value="__('Notes / Observations')" />
                                <textarea id="note" name="note" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Describe the field condition...">{{ old('note') }}</textarea>
                                <x-input-error :messages="$errors->get('note')" class="mt-2" />
                            </div>
                        </div>
                        <div class="mt-4">
                            <x-primary-button>
                                {{ __('Submit Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
            @endcan

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Activity Timeline</h3>
                    @if($field->updates->count() > 0)
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach($field->updates as $update)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        Stage changed from <span class="font-medium text-gray-900">{{ $update->previous_stage ?? 'None' }}</span> to <span class="font-medium text-gray-900">{{ $update->new_stage }}</span> by <span class="font-medium text-gray-900">{{ $update->updater->name }}</span>
                                                    </p>
                                                    @if($update->note)
                                                        <div class="mt-2 text-sm text-gray-700 italic">
                                                            "{{ $update->note }}"
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                    <time datetime="{{ $update->observed_at }}">{{ $update->observed_at->format('M j, Y H:i') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No activity recorded yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
