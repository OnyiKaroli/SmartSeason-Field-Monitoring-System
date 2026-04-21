<x-app-layout>
    <x-slot name="title">Field Agent Dashboard - {{ config('app.name') }}</x-slot>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">My Dashboard</h1>
                <p class="text-sm text-gray-500 mt-0.5">Manage your assigned fields and updates</p>
            </div>
        </div>
    </x-slot>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Assigned Fields</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $summary['total_assigned'] }}</p>
            <div class="mt-2 text-xs text-gray-400">Total fields under your care</div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm border-l-4 border-l-emerald-500">
            <p class="text-sm font-medium text-gray-500">Active</p>
            <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $summary['active_count'] }}</p>
            <div class="mt-2 text-xs text-gray-400">Progressing normally</div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm border-l-4 border-l-amber-500">
            <p class="text-sm font-medium text-gray-500">At Risk</p>
            <p class="text-3xl font-bold text-amber-600 mt-1">{{ $summary['at_risk_count'] }}</p>
            <div class="mt-2 text-xs text-gray-400">Requires attention</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Fields Needing Updates -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-amber-50">
                <h3 class="text-lg font-medium text-amber-900">⚠️ Needs Update</h3>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                    {{ $summary['needs_updates']->count() }} Fields
                </span>
            </div>
            @if($summary['needs_updates']->isEmpty())
                <div class="p-12 text-center text-gray-500">No fields currently needing urgent updates.</div>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($summary['needs_updates'] as $field)
                        <li class="p-4 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $field->name }}</p>
                                    <p class="text-xs text-amber-600">{{ $field->status_reason }}</p>
                                </div>
                                <a href="{{ route('fields.show', $field) }}" class="inline-flex items-center px-3 py-1 border border-amber-300 shadow-sm text-xs font-medium rounded-md text-amber-700 bg-white hover:bg-amber-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                    Update Now
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Recent Personal Activity -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Your Recent Activity</h3>
            </div>
            @if($summary['recent_updates']->isEmpty())
                <div class="p-12 text-center text-gray-500">You haven't submitted any updates yet.</div>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($summary['recent_updates'] as $update)
                        <li class="p-4">
                            <div class="flex space-x-3">
                                <div class="flex-1 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-medium text-gray-900">{{ $update->field->name }}</h3>
                                        <p class="text-xs text-gray-500">{{ $update->created_at->diffForHumans() }}</p>
                                    </div>
                                    <p class="text-sm text-gray-500">
                                        Changed stage to <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">{{ $update->new_stage }}</span>
                                    </p>
                                    @if($update->note)
                                        <p class="text-xs text-gray-400 italic">"{{ Str::limit($update->note, 40) }}"</p>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Quick Access Assigned Fields -->
    <div class="mt-8 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Your Assigned Fields</h3>
        </div>
        @if($summary['fields']->isEmpty())
            <div class="p-12 text-center text-gray-500">No fields assigned to you yet.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Crop</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($summary['fields'] as $field)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $field->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $field->crop_type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $field->current_stage === 'Planted' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $field->current_stage === 'Growing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $field->current_stage === 'Ready' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $field->current_stage === 'Harvested' ? 'bg-gray-100 text-gray-800' : '' }}
                                    ">
                                        {{ $field->current_stage }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $field->status === 'Active' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $field->status === 'At Risk' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $field->status === 'Completed' ? 'bg-blue-100 text-blue-800' : '' }}
                                    ">
                                        {{ $field->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('fields.show', $field) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
