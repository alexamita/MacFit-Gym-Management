<?php
// Migration for creating the bundles table for the gym management system, including fields for bundle name, location, start time, session duration, description, and foreign keys to categories and gyms to store information about different fitness bundles that users can subscribe to, with details about the bundle and its association to a category and gym
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
        Schema::create('bundles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->time('start_time');
            // $table->Time('duration');
            $table->unsignedInteger('session_duration')->comment('Duration in minutes');
            $table->string('description', 1000);


            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('gym_id');

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('gym_id')->references('id')->on('gyms');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bundles');
    }
};
