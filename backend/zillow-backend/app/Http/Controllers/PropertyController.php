<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Add this
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    use AuthorizesRequests; // Add this trait

    /**
     * Display a listing of available properties.
     */
    public function index(Request $request)
    {
        $properties = Property::query()
            ->where('status', 'available')
            ->orderBy('is_sponsored', 'desc')
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->paginate(10);

        return response()->json($properties);
    }

    /**
     * Store a newly created property in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:house,apartment,condo,land,commercial',
            'listing_type' => 'required|in:sale,rent',
            'price' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2',
            'zip_code' => 'required|string|max:10',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'square_feet' => 'nullable|integer|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'furnished' => 'required|in:Yes,No',
            'lease_term_months' => 'nullable|integer|min:1',
            'amenities' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        if (!$user->hasAnyRole(['seller', 'agent', 'broker', 'homeowner'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties', 'public');
                $imagePaths[] = basename($path);
            }
        }
        $validated['images'] = $imagePaths;

        $property = $user->properties()->create($validated);
        $property->images = array_map(fn($image) => Storage::url('properties/' . $image), $property->images);
        return response()->json($property, 201);
    }

    /**
     * Display the specified property.
     */
    public function show(Property $property)
    {
        if ($property->status !== 'available' && !Auth::user()->can('update', $property)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $property->load('user');
        return response()->json($property);
    }

    /**
     * Update the specified property in storage.
     */
    public function update(Request $request, Property $property)
    {
        $this->authorize('update', $property);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'type' => 'sometimes|in:house,apartment,condo,land,commercial',
            'listing_type' => 'sometimes|in:sale,rent',
            'status' => 'sometimes|in:available,pending,sold,rented',
            'price' => 'sometimes|numeric|min:0',
            'address' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:100',
            'state' => 'sometimes|string|size:2',
            'zip_code' => 'sometimes|string|max:10',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'square_feet' => 'nullable|integer|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'furnished' => 'sometimes|in:Yes,No',
            'lease_term_months' => 'nullable|integer|min:1',
            'amenities' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('images')) {
            if ($property->images) {
                foreach ($property->images as $oldImage) {
                    Storage::disk('public')->delete('properties/' . $oldImage);
                }
            }
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties', 'public');
                $imagePaths[] = basename($path);
            }
            $validated['images'] = $imagePaths;
        }

        $property->update($validated);
        $property->images = $property->images
            ? array_map(fn($image) => Storage::url('properties/' . $image), $property->images)
            : [];
        return response()->json($property);
    }

    /**
     * Remove the specified property from storage.
     */
    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);

        if ($property->images) {
            foreach ($property->images as $image) {
                Storage::disk('public')->delete('properties/' . $image);
            }
        }

        $property->delete();
        return response()->json(['message' => 'Property deleted successfully'], 200);
    }

    /**
     * Promote the specified property as sponsored.
     */
    public function promote(Request $request, Property $property)
    {
        $this->authorize('update', $property);

        $validated = $request->validate([
            'duration_days' => 'required|integer|min:1|max:365',
        ]);

        $property->update(['is_sponsored' => true]);
        return response()->json([
            'message' => 'Property promoted successfully',
            'property' => $property,
        ]);
    }
}