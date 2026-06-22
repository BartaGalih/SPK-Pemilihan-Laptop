<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Laptop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price', 'ram', 'cpu_score', 'weight_kg', 'storage', 'battery',
    ];

    protected $casts = [
        'price'     => 'integer',
        'ram'       => 'integer',
        'cpu_score' => 'integer',
        'weight_kg' => 'float',
        'storage'   => 'integer',
        'battery'   => 'float',
    ];

    public function mfepResult()
    {
        return $this->hasOne(MfepResult::class);
    }

    public function getPriceFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
