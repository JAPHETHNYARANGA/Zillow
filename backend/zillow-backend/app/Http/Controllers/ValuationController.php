<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Valuation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValuationController extends Controller
{
    public function index()
    {
        $valuations = Valuation::whereHas('property', function ($query) {
            $query->where('user_id', Auth::id());
        })->with('property')->paginate(10);
        return response()->json($valuations);
    }

    public function store(Request $request)
    {
        $request->validate(['property_id' => 'required|exists:properties,id']);
        $property = Property::findOrFail($request->property_id);

        if ($property->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $estimatedValue = $this->calculateValuation($property);
        $valuation = Valuation::create([
            'property_id' => $property->id,
            'estimated_value' => $estimatedValue,
            'trend_data' => json_encode(['last_year' => $estimatedValue * 0.95, 'trend' => 'up']),
            'calculated_at' => now(),
        ]);

        return response()->json($valuation, 201);
    }

    public function show(Valuation $valuation)
    {
        if ($valuation->property->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $valuation->load('property');
        return response()->json($valuation);
    }

    public function update(Request $request, Valuation $valuation)
    {
        if ($valuation->property->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $validated = $request->validate([
            'estimated_value' => 'sometimes|numeric|min:0',
            'trend_data' => 'sometimes|json',
        ]);
        $valuation->update($validated);
        return response()->json($valuation);
    }

    public function destroy(Valuation $valuation)
    {
        if ($valuation->property->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $valuation->delete();
        return response()->json(['message' => 'Valuation deleted successfully']);
    }

    private function calculateValuation(Property $property)
    {
        $baseValue = $property->price ?? 100000;
        $sizeFactor = $property->square_feet ? $property->square_feet * 100 : 0;
        $bedroomFactor = $property->bedrooms ? $property->bedrooms * 10000 : 0;
        return $baseValue + $sizeFactor + $bedroomFactor;
    }
}