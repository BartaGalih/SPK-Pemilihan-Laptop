<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MfepResult extends Model
{
    protected $fillable = [
        'laptop_id', 'rank',
        'nef_c1','nef_c2','nef_c3','nef_c4','nef_c5','nef_c6',
        'nbe_c1','nbe_c2','nbe_c3','nbe_c4','nbe_c5','nbe_c6',
        'tbe', 'calculated_at',
    ];

    protected $casts = [
        'calculated_at' => 'datetime',
    ];

    public function laptop()
    {
        return $this->belongsTo(Laptop::class);
    }
}
