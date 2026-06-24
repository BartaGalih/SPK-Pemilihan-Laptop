<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MfepResultDetail extends Model
{
    protected $fillable = ['mfep_result_id', 'criteria_id', 'nef', 'nbe'];

    protected $casts = [
        'nef' => 'float',
        'nbe' => 'float',
    ];

    public function result()
    {
        return $this->belongsTo(MfepResult::class, 'mfep_result_id');
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class, 'criteria_id');
    }
}
