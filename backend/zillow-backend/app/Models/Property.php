<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'description', 'type', 'listing_type', 'status', 'price',
        'address', 'city', 'state', 'zip_code', 'bedrooms', 'bathrooms',
        'square_feet', 'latitude', 'longitude', 'furnished', 'lease_term_months',
        'is_sponsored', 'amenities', 'images', // Added is_sponsored
    ];

    protected $casts = [
        'images' => 'array',
        'amenities' => 'array',
        'price' => 'decimal:2',
        'latitude' => 'float',
        'longitude' => 'float',
        'is_sponsored' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
}