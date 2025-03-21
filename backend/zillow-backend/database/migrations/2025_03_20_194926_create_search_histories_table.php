<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('location')->nullable();
            $table->decimal('price_min', 15, 2)->nullable();
            $table->decimal('price_max', 15, 2)->nullable();
            $table->string('type')->nullable();
            $table->integer('bedrooms')->nullable();
            $table->json('amenities')->nullable();
            $table->decimal('latitude', 10, 7)->nullable(); // Added
            $table->decimal('longitude', 10, 7)->nullable(); // Added
            $table->float('radius')->nullable(); // Added
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_histories');
    }
};