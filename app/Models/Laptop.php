<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Laptop extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function values()
    {
        return $this->hasMany(LaptopValue::class);
    }

    public function mfepResult()
    {
        return $this->hasOne(MfepResult::class);
    }

    /**
     * Ambil nilai laptop untuk sebuah kriteria (berdasarkan criteria_id).
     */
    public function valueFor($criteriaId)
    {
        return optional($this->values->firstWhere('criteria_id', $criteriaId))->value;
    }
}
