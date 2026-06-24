<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SnapshotCriterion extends Model
{
    protected $table = 'snapshot_criteria';

    protected $fillable = [
        'snapshot_id', 'code', 'name', 'unit', 'type', 'weight',
    ];

    protected $casts = [
        'weight' => 'float',
    ];

    public function snapshot()
    {
        return $this->belongsTo(CalculationSnapshot::class, 'snapshot_id');
    }
}
