<x-app-layout>
    <x-slot name="title">Admin Dashboard - {{ config('app.name') }}</x-slot>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Admin Dashboard</h1>
                <p class="text-sm text-gray-500 mt-0.5">Overview of all fields and agent activity</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('fields.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Add New Field
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Total Fields</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $summary['total_fields'] }}</p>
            <div class="mt-2 text-xs text-gray-400">All registered fields</div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm border-l-4 border-l-emerald-500">
            <p class="text-sm font-medium text-gray-500">Active</p>
            <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $summary['status_breakdown']['Active'] }}</p>
            <div class="mt-2 text-xs text-gray-400">Progressing normally</div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm border-l-4 border-l-amber-500">
            <p class="text-sm font-medium text-gray-500">At Risk</p>
            <p class="text-3xl font-bold text-amber-600 mt-1">{{ $summary['status_breakdown']['At Risk'] }}</p>
            <div class="mt-2 text-xs text-gray-400">Needs attention</div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm border-l-4 border-l-blue-500">
            <p class="text-sm font-medium text-gray-500">Completed</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $summary['status_breakdown']['Completed'] }}</p>
            <div class="mt-2 text-xs text-gray-400">Harvested fields</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
        <!-- Stage Breakdown -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm h-full">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Stage Breakdown</h3>
                </div>
                <div class="p-6 space-y-4">
                    @foreach($summary['stage_breakdown'] as $stage => $count)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700">{{ $stage }}</span>
                                <span class="text-gray-500">{{ $count }} fields</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                @php
                                    $percentage = $summary['total_fields'] > 0 ? ($count / $summary['total_fields']) * 100 : 0;
                                @endphp
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Agent Activity Summary -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm h-full overflow-hidden">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Agent Activity</h3>
                </div>
                @if($summary['agent_activity']->isEmpty())
                    <div class="p-12 text-center text-gray-500">No field agents found.</div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Fields</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Last Activity</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($summary['agent_activity'] as $activity)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $activity['agent']->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $activity['agent']->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $activity['fields_count'] }} fields
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                        {{ $activity['last_update_at'] ? $activity['last_update_at']->diffForHumans() : 'Never' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Fields Needing Attention -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-amber-50">
                <h3 class="text-lg font-medium text-amber-900">⚠️ Needs Attention</h3>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                    {{ $summary['status_breakdown']['At Risk'] }} Total
                </span>
            </div>
            @if($summary['needs_attention']->isEmpty())
                <div class="p-12 text-center text-gray-500">All fields are progressing normally. Great job!</div>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($summary['needs_attention'] as $field)
                        <li class="p-4 hover:bg-gray-50 transition">
                            <a href="{{ route('fields.show', $field) }}" class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $field->name }}</p>
                                    <p class="text-xs text-amber-600">{{ $field->status_reason }}</p>
                                </div>
                                <div class="text-xs text-gray-500">
                                    Agent: {{ $field->assignedAgent->name ?? 'Unassigned' }}
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Recent Updates -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                <a href="{{ route('fields.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium text-xs">View All &rarr;</a>
            </div>
            @if($summary['recent_updates']->isEmpty())
                <div class="p-12 text-center text-gray-500">No recent activity recorded.</div>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($summary['recent_updates'] as $update)
                        <li class="p-4">
                            <div class="flex space-x-3">
                                <div class="shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-indigo-600 text-xs font-bold">{{ substr($update->updater->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-medium">{{ $update->updater->name }}</h3>
                                        <p class="text-xs text-gray-500">{{ $update->created_at->diffForHumans() }}</p>
                                    </div>
                                    <p class="text-sm text-gray-500">
                                        Updated <span class="font-semibold text-gray-700">{{ $update->field->name }}</span> to 
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">{{ $update->new_stage }}</span>
                                    </p>
                                    @if($update->note)
                                        <p class="text-xs text-gray-400 italic">"{{ Str::limit($update->note, 50) }}"</p>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
