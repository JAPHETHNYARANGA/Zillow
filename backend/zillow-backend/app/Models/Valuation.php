<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Valuation extends Model
{
    protected $fillable = [
        'property_id', 'estimated_value', 'trend_data', 'calculated_at'
    ];

    protected $casts = [
        'trend_data' => 'array',
        'calculated_at' => 'datetime',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}