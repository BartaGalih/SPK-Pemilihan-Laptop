<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SnapshotLaptop extends Model
{
    protected $fillable = ['snapshot_id', 'name', 'rank', 'tbe'];

    protected $casts = [
        'tbe' => 'float',
    ];

    public function snapshot()
    {
        return $this->belongsTo(CalculationSnapshot::class, 'snapshot_id');
    }

    public function values()
    {
        return $this->hasMany(SnapshotLaptopValue::class, 'snapshot_laptop_id');
    }
}
