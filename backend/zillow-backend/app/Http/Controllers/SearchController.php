<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\SearchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = Property::query()->where('status', 'available');

        // Apply filters
        if ($request->has('location')) {
            $query->where(function ($q) use ($request) {
                $q->where('city', 'like', '%' . $request->location . '%')
                  ->orWhere('state', 'like', '%' . $request->location . '%')
                  ->orWhere('zip_code', $request->location);
            });
        }

        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->has('type')) {
            $query->whereIn('type', explode(',', $request->type));
        }

        if ($request->has('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        if ($request->has('amenities')) {
            $amenities = explode(',', $request->amenities);
            $query->whereJsonContains('amenities', $amenities);
        }

        // Geospatial search (requires PostGIS or similar)
      //  if ($request->has('latitude') && $request->has('longitude') && $request->has('radius')) {
        //    $lat = $request->latitude;
          //  $lon = $request->longitude;
            //$radius = $request->radius; // in kilometers
           // $query->whereRaw("ST_Distance_Sphere(
             //   ST_MakePoint(longitude, latitude),
             //   ST_MakePoint(?, ?)
           // ) <= ? * 1000", [$lon, $lat, $radius]);
       // }

        // Save search history for authenticated users
        if (Auth::check()) {
            SearchHistory::create([
                'user_id' => Auth::id(),
                'location' => $request->location,
                'price_min' => $request->price_min,
                'price_max' => $request->price_max,
                'type' => $request->type,
                'bedrooms' => $request->bedrooms,
                'amenities' => $request->amenities ? explode(',', $request->amenities) : null,
                'latitude' => $request->latitude,  // Optional, if migration updated
                'longitude' => $request->longitude, // Optional
                'radius' => $request->radius,      // Optional
            ]);
        }

        // Load valuation relationship (for future Valuation module)
        $properties = $query->with(['user', 'valuation'])->paginate(10);

        return response()->json([
            'properties' => $properties,
            'map_data' => $this->generateMapData($properties),
        ]);
    }

    private function generateMapData($properties)
    {
        return $properties->map(function ($property) {
            return [
                'id' => $property->id,
                'title' => $property->title,
                'latitude' => $property->latitude,
                'longitude' => $property->longitude,
                'price' => $property->price,
                'estimated_value' => $property->valuation ? $property->valuation->estimated_value : null, // Valuation integration
            ];
        });
    }

    public function neighborhoodInsights(Request $request)
    {
        $request->validate([
            'zip_code' => 'required|string|max:10',
        ]);

        // Mock data or integrate with an external API (e.g., Google Maps, CrimeoMeter)
        $insights = [
            'zip_code' => $request->zip_code,
            'schools' => 'Data from API or DB',
            'crime_rate' => 'Data from API or DB',
            'transport' => 'Data from API or DB',
        ];

        return response()->json($insights);
    }
}