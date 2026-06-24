<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Criteria extends Model
{
    use SoftDeletes;

    protected $table = 'criteria';
    protected $fillable = ['code', 'name', 'unit', 'type', 'weight'];

    protected $casts = [
        'weight' => 'float',
    ];

    public function laptopValues()
    {
        return $this->hasMany(LaptopValue::class, 'criteria_id');
    }

    public function resultDetails()
    {
        return $this->hasMany(MfepResultDetail::class, 'criteria_id');
    }
}
