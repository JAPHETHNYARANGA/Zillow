<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    protected $fillable = ['user_id', 'location', 'price_min', 'price_max', 'type', 'bedrooms', 'amenities'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}