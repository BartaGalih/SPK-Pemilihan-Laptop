<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MfepResult extends Model
{
    protected $fillable = ['laptop_id', 'rank', 'tbe', 'calculated_at'];

    protected $casts = [
        'tbe'           => 'float',
        'calculated_at' => 'datetime',
    ];

    public function laptop()
    {
        return $this->belongsTo(Laptop::class);
    }

    public function details()
    {
        return $this->hasMany(MfepResultDetail::class);
    }

    /**
     * Ambil detail (nef/nbe) untuk sebuah kriteria.
     */
    public function detailFor($criteriaId)
    {
        return $this->details->firstWhere('criteria_id', $criteriaId);
    }
}
