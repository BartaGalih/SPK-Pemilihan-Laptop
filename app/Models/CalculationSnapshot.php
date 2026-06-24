<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalculationSnapshot extends Model
{
    protected $fillable = [
        'title', 'note', 'total_laptops', 'total_criteria', 'calculated_at',
    ];

    protected $casts = [
        'calculated_at' => 'datetime',
    ];

    public function criteria()
    {
        return $this->hasMany(SnapshotCriterion::class, 'snapshot_id');
    }

    public function laptops()
    {
        return $this->hasMany(SnapshotLaptop::class, 'snapshot_id');
    }
}
