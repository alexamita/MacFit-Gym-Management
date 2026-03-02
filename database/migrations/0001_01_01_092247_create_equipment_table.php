<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Equipment inventory per gym.
     *
     * - manufacturer_serial_no: globally unique (manufacturer-issued)
     * - asset_code: internal gym label (unique per gym when provided)
     */
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            // column methods
            $table->id();

            // Ownership / scope
            $table->foreignId('gym_id')
                ->constrained('gyms')
                ->cascadeOnDelete();

            // Ownership / scope
            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            // Equipment details
            $table->string('name');
            $table->text('usage_notes')->nullable();

            // Manufacturer-issued serial number (global uniqueness)
            $table->string('manufacturer_serial_no',100)->unique();

            // Internal label used by a gym (optional)
            $table->string('asset_code',100)->nullable(); // Internal label

            $table->decimal('value', 10, 2);
            $table->enum('status', ['active', 'under_maintenance', 'faulty','decommisioned'])->default('active');

            $table->timestamps();
             // Internal label used by a gym (optional)
            $table->unique(['gym_id', 'asset_code']);
            // Helpful for common queries (e.g., list faulty equipment)
            $table->index('status');
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
