<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Field;
use App\Http\Requests\StoreFieldRequest;
use App\Http\Requests\UpdateFieldRequest;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Field::with(['assignedAgent', 'creator']);

        if (!auth()->user()->isAdmin()) {
            $query->where('assigned_agent_id', auth()->id());
        }

        $fields = $query->latest()->paginate(10);
        return view('fields.index', compact('fields'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Field::class);
        $fieldAgents = \App\Models\User::where('role', 'field_agent')->orderBy('name')->get();
        return view('fields.create', compact('fieldAgents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFieldRequest $request)
    {
        $validated = $request->validated();
        $validated['created_by'] = auth()->id();
        $validated['current_stage'] = $validated['current_stage'] ?? 'Planted';

        Field::create($validated);

        return redirect()->route('fields.index')
            ->with('success', 'Field created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Field $field)
    {
        $this->authorize('view', $field);
        $field->load(['assignedAgent', 'creator', 'updates.updater']);
        return view('fields.show', compact('field'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Field $field)
    {
        $this->authorize('update', $field);
        $fieldAgents = \App\Models\User::where('role', 'field_agent')->orderBy('name')->get();
        return view('fields.edit', compact('field', 'fieldAgents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFieldRequest $request, Field $field)
    {
        $this->authorize('update', $field);
        $field->update($request->validated());

        return redirect()->route('fields.show', $field)
            ->with('success', 'Field updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Field $field)
    {
        $this->authorize('delete', $field);
        $field->delete();

        return redirect()->route('fields.index')
            ->with('success', 'Field deleted successfully.');
    }
}
