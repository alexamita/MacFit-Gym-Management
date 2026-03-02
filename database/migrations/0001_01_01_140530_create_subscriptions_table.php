<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Subscriptions:
     * - Belongs to a user
     * - Belongs to a bundle (bundle may be global or gym-specific)
     * - Gym is derived via bundle->gym_id (no gym_id stored here)
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
             // column methods
            $table->id();

            // Relationships
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Keep subscription history even if bundle is removed
            $table->foreignId('bundle_id')
                ->constrained('bundles')
                ->restrictOnDelete();

            // Subscription lifecycle
            $table->dateTime('starts_at');
            $table->dateTime('expires_at');

            $table->enum('status', ['active', 'cancelled', 'expired'])
                ->default('active');

            $table->timestamps();

             // Performance indexes
            $table->index(['user_id', 'status']);
            $table->index('expires_at');

            // Prevent duplicate identical subscriptions
            $table->unique([
                'user_id',
                'bundle_id',
                'starts_at'
            ], 'subscriptions_unique_user_bundle_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
