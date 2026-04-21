<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Fields') }}
            </h2>
            @can('create', \App\Models\Field::class)
            <a href="{{ route('fields.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                Create Field
            </a>
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

            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('fields.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                        <div>
                            <x-input-label for="crop_type" :value="__('Crop Type')" />
                            <x-text-input id="crop_type" name="crop_type" type="text" class="mt-1 block w-full" :value="request('crop_type')" placeholder="e.g. Corn" />
                        </div>
                        <div>
                            <x-input-label for="stage" :value="__('Stage')" />
                            <select id="stage" name="stage" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">All Stages</option>
                                @foreach(\App\Models\Field::STAGES as $stage)
                                    <option value="{{ $stage }}" {{ request('stage') === $stage ? 'selected' : '' }}>{{ $stage }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">All Statuses</option>
                                <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="At Risk" {{ request('status') === 'At Risk' ? 'selected' : '' }}>At Risk</option>
                                <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        @if(auth()->user()->isAdmin())
                        <div>
                            <x-input-label for="agent_id" :value="__('Agent')" />
                            <select id="agent_id" name="agent_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">All Agents</option>
                                @foreach($fieldAgents as $agent)
                                    <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="flex space-x-2">
                            <x-primary-button>
                                {{ __('Filter') }}
                            </x-primary-button>
                            <a href="{{ route('fields.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Reset') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if ($fields->isEmpty())
                        <p class="text-gray-500 text-center py-4">No fields found matching your criteria.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Crop Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Planting Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stage</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Agent</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($fields as $field)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <div class="flex items-center">
                                                    {{ $field->name }}
                                                    @if($field->needs_attention)
                                                        <span title="Needs Attention" class="ml-2 text-red-500">
                                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $field->crop_type }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $field->planting_date->format('Y-m-d') }}</td>
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $field->assignedAgent ? $field->assignedAgent->name : 'Unassigned' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('fields.show', $field) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                                @can('update', $field)
                                                <a href="{{ route('fields.edit', $field) }}" class="text-gray-600 hover:text-gray-900">Edit</a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $fields->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
