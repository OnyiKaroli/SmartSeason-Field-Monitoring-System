<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Field') }}: {{ $field->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('fields.update', $field) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="name" value="Field Name" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $field->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="crop_type" value="Crop Type" />
                            <x-text-input id="crop_type" class="block mt-1 w-full" type="text" name="crop_type" :value="old('crop_type', $field->crop_type)" required />
                            <x-input-error :messages="$errors->get('crop_type')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="planting_date" value="Planting Date" />
                            <x-text-input id="planting_date" class="block mt-1 w-full" type="date" name="planting_date" :value="old('planting_date', $field->planting_date->format('Y-m-d'))" required max="{{ date('Y-m-d') }}" />
                            <x-input-error :messages="$errors->get('planting_date')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="current_stage" value="Current Stage" />
                            <select id="current_stage" name="current_stage" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="Planted" {{ old('current_stage', $field->current_stage) == 'Planted' ? 'selected' : '' }}>Planted</option>
                                <option value="Growing" {{ old('current_stage', $field->current_stage) == 'Growing' ? 'selected' : '' }}>Growing</option>
                                <option value="Ready" {{ old('current_stage', $field->current_stage) == 'Ready' ? 'selected' : '' }}>Ready</option>
                                <option value="Harvested" {{ old('current_stage', $field->current_stage) == 'Harvested' ? 'selected' : '' }}>Harvested</option>
                            </select>
                            <x-input-error :messages="$errors->get('current_stage')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a class="text-sm text-gray-600 hover:text-gray-900 mr-4" href="{{ route('fields.show', $field) }}">
                                Cancel
                            </a>
                            <x-primary-button>
                                Update Field
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
