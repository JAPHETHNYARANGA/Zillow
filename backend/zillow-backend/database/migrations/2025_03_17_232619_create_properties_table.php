<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['house', 'apartment', 'condo', 'land', 'commercial'])->default('house');
            $table->enum('status', ['available', 'pending', 'sold', 'rented'])->default('available');
            $table->enum('listing_type', ['sale', 'rent'])->default('sale');
            $table->decimal('price', 15, 2);
            $table->string('address');
            $table->string('city');
            $table->string('state', 2);
            $table->string('zip_code', 10);
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('square_feet')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('furnished', ['Yes', 'No'])->default('No');
            $table->integer('lease_term_months')->nullable();
            $table->boolean('is_sponsored')->default(false);
            $table->timestamp('sponsored_until')->nullable(); // Added from remote
            $table->string('listing_status')->default('active'); // Added from remote
            $table->json('amenities')->nullable();
            $table->json('images')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'type', 'price']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};