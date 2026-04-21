<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StoreFieldUpdateRequest;
use App\Models\Field;

class FieldUpdateController extends Controller
{
    public function store(StoreFieldUpdateRequest $request, Field $field)
    {
        $this->authorize('updateStatus', $field);

        $field->updates()->create([
            'updated_by' => auth()->id(),
            'previous_stage' => $field->current_stage,
            'new_stage' => $request->new_stage,
            'note' => $request->note,
            'observed_at' => $request->observed_at,
        ]);

        $field->update([
            'current_stage' => $request->new_stage,
        ]);

        return redirect()->route('fields.show', $field)
            ->with('success', 'Field stage updated successfully.');
    }
}
