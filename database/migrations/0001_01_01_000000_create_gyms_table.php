<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gyms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Gym/branch display name (unique across the system)
            $table->string('location')->nullable(); // Physical address / area (optional)
            $table->string('phone_number')->nullable();  // Primary contact for this gym (optional)
            $table->text('description')->nullable();  // Optional details/notes
            $table->timestamps();
            $table->softDeletes();


            // Optional: add index if you will search by location/phone often
            // $table->index('location');
            // $table->index('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gyms');
    }
};
