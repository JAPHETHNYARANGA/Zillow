<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with('user')->where('status', 'available')->get();
        $properties->each(function ($property) {
            $property->images = $property->images
                ? array_map(fn($image) => Storage::url('properties/' . $image), $property->images)
                : [];
        });
        return response()->json($properties);
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
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate uploaded files
        ]);

        $user = Auth::user();
        if (!$user->hasAnyRole(['seller', 'agent'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties', 'public');
                $imagePaths[] = basename($path); // Store just the filename
            }
        }
        $validated['images'] = $imagePaths;

        $property = $user->properties()->create($validated);
        $property->images = array_map(fn($image) => Storage::url('properties/' . $image), $property->images);
        return response()->json($property, 201);
    }

    public function show(Property $property)
    {
        $property->images = $property->images
            ? array_map(fn($image) => Storage::url('properties/' . $image), $property->images)
            : [];
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
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate uploaded files
        ]);

        // Handle image uploads (replace existing images)
        if ($request->hasFile('images')) {
            // Delete old images if they exist
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

    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);

        // Delete associated images
        if ($property->images) {
            foreach ($property->images as $image) {
                Storage::disk('public')->delete('properties/' . $image);
            }
        }

        $property->delete(); // Soft delete
        return response()->json(['message' => 'Property deleted']);
    }
}