<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        // Ensure there are users to associate properties with
        $users = User::all();
        if ($users->isEmpty()) {
            // Create 5 users if none exist
            User::factory()->count(5)->create();
            $users = User::all();
        }

        // Sample amenities list to randomly assign
        $amenitiesOptions = [
            ['pool', 'gym', 'parking'],
            ['garden', 'fireplace'],
            ['pool', 'security'],
            ['gym', 'parking'],
            []
        ];

        // Create 20 properties
        foreach (range(1, 20) as $index) {
            Property::create([
                'user_id' => $users->random()->id, // Random user
                'title' => fake()->randomElement([
                    'Cozy Cottage', 'Modern Apartment', 'Spacious Condo', 
                    'Vacant Land', 'Commercial Space'
                ]) . " #$index",
                'description' => fake()->paragraph(),
                'type' => fake()->randomElement(['house', 'apartment', 'condo', 'land', 'commercial']),
                'status' => fake()->randomElement(['available', 'pending', 'sold', 'rented']),
                'price' => fake()->randomFloat(2, 50000, 1000000), // $50K to $1M
                'address' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->stateAbbr(),
                'zip_code' => fake()->postcode(),
                'bedrooms' => fake()->optional(0.8, null)->numberBetween(1, 5),
                'bathrooms' => fake()->optional(0.8, null)->numberBetween(1, 3),
                'square_feet' => fake()->optional(0.8, null)->numberBetween(500, 5000),
                'latitude' => fake()->latitude(),
                'longitude' => fake()->longitude(),
                'furnished' => fake()->randomElement(['Yes', 'No']),
                'amenities' => fake()->randomElement($amenitiesOptions),
                'images' => [
                    fake()->imageUrl(640, 480, 'house'),
                    fake()->imageUrl(640, 480, 'house'),
                ],
            ]);
        }
    }
}