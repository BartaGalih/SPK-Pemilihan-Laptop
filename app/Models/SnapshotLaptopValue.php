<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SnapshotLaptopValue extends Model
{
    protected $fillable = [
        'snapshot_laptop_id', 'snapshot_criteria_id', 'value', 'nef', 'nbe',
    ];

    protected $casts = [
        'value' => 'float',
        'nef'   => 'float',
        'nbe'   => 'float',
    ];

    public function laptop()
    {
        return $this->belongsTo(SnapshotLaptop::class, 'snapshot_laptop_id');
    }

    public function criteria()
    {
        return $this->belongsTo(SnapshotCriterion::class, 'snapshot_criteria_id');
    }
}
