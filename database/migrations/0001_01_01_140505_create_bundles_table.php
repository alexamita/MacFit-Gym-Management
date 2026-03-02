<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bundles table:
     * - gym_id NULL => truly global bundle
     * - gym_scope is generated: COALESCE(gym_id, 0)
     * - unique(gym_scope, slug) enforces uniqueness for:
     *   - global bundles (scope=0)
     *   - gym bundles (scope=gym_id)
     */
    public function up(): void
    {
        Schema::create('bundles', function (Blueprint $table) {
            $table->id();

             // NULL means global bundle available to all gyms
            $table->foreignId('gym_id')->nullable()
                ->constrained('gyms')
                ->nullOnDelete();
            // Categories are global
            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            $table->string('name');
            $table->string('slug');

            $table->text('description')->nullable();

            $table->string('gym_zone')
            ->nullable()
            ->comment('Zone or room where session takes place'); // replace with zone_id FK later.

            $table->time('start_time')->nullable();

            $table->unsignedInteger('session_duration')
            ->comment('Duration per session in minutes');

            $table->decimal('price', 12,2);
            $table->char('currency', 3)->default('KES');

            $table->timestamps();

            /**
             * MySQL NULL + UNIQUE fix:
             * gym_scope = COALESCE(gym_id, 0)
             */
            $table->unsignedBigInteger('gym_scope')
                ->virtualAs('COALESCE(`gym_id`, 0)');

            // Correct uniqueness for both global and gym bundles
            $table->unique(['gym_scope', 'slug'], 'bundles_scope_slug_unique');

            // Useful for filtering
            $table->index(['gym_id', 'category_id'], 'bundles_gym_category_index');
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
