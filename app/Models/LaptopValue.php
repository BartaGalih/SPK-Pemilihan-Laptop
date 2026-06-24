<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaptopValue extends Model
{
    protected $fillable = ['laptop_id', 'criteria_id', 'value'];

    protected $casts = [
        'value' => 'float',
    ];

    public function laptop()
    {
        return $this->belongsTo(Laptop::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class, 'criteria_id');
    }
}
