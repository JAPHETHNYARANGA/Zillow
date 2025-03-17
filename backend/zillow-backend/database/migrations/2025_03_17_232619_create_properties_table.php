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
            $table->decimal('price', 15, 2);
            $table->string('address');
            $table->string('city');
            $table->string('state', 2);
            $table->string('zip_code', 10);
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('square_feet')->nullable();
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