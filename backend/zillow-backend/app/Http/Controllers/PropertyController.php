<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function index()
    {
        return response()->json(Property::with('user')->where('status', 'available')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:house,apartment,condo,land,commercial',
            'price' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2',
            'zip_code' => 'required|string|max:10',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'square_feet' => 'nullable|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'string',
        ]);

        $user = Auth::user();
        if (!$user->hasAnyRole(['seller', 'agent'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $property = $user->properties()->create($validated);
        return response()->json($property, 201);
    }

    public function show(Property $property)
    {
        return response()->json($property->load('user'));
    }

    public function update(Request $request, Property $property)
    {
        $this->authorize('update', $property);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'type' => 'sometimes|in:house,apartment,condo,land,commercial',
            'status' => 'sometimes|in:available,pending,sold,rented',
            'price' => 'sometimes|numeric|min:0',
            'address' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:100',
            'state' => 'sometimes|string|size:2',
            'zip_code' => 'sometimes|string|max:10',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'square_feet' => 'nullable|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'string',
        ]);

        $property->update($validated);
        return response()->json($property);
    }

    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);
        $property->delete();
        return response()->json(['message' => 'Property deleted']);
    }
}