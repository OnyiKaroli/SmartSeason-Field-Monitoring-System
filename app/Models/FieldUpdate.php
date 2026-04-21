<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldUpdate extends Model
{
    use HasFactory;
    protected $fillable = [
        'field_id',
        'updated_by',
        'previous_stage',
        'new_stage',
        'note',
        'observed_at',
    ];

    protected $casts = [
        'observed_at' => 'datetime',
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
