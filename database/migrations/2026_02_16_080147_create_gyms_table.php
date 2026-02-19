<?php
// Migration for creating the gyms table for the gym management system, including fields for gym name, location (longitude and latitude), and description to store information about different gym locations that can have bundles, equipment, and subscriptions associated with them
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
            $table->string('name')->unique();
            $table->decimal('longitude', 9,6);
            $table->decimal('latitude',9,6);
            $table->string('description',1000);
            $table->timestamps();
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
