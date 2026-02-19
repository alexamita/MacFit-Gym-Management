<?php
// Migration for creating the equipment table for the gym management system, including fields for equipment name, usage, model number, value, status, and foreign key to gyms to store information about different fitness equipment that can be associated with gyms
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
        Schema::create('equipment', function (Blueprint $table) {
            // column methods
            $table->id();
            $table->string('name');
            $table->string('usage');
            $table->string('model_no',100);
            $table->decimal('value', 10, 2);
            $table->enum('status', ['ACTIVE', 'UNDER_MAINTENANCE', 'FAULTY','DECOMMISSIONED'])->default('ACTIVE');
            $table->unsignedBigInteger('gym_id');

            $table->foreign('gym_id')->references('id')->on('gyms');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
