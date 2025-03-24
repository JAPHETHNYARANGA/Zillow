<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('valuations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->decimal('estimated_value', 15, 2);
            $table->json('trend_data')->nullable();
            $table->timestamp('calculated_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('valuations');
    }
};