<?php
// Migration for creating the subscriptions table for the gym management system, including fields for subscription date, status, and foreign keys to users and bundles to store information about user subscriptions to fitness bundles
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
        Schema::create('subscriptions', function (Blueprint $table) {
             // column methods
            $table->id();
            $table->dateTime('subscribed_at');
            $table->enum('status', ['ACTIVE', 'CANCELLED', 'EXPIRED'])->default('ACTIVE');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('bundle_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('bundle_id')->references('id')->on('bundles');
            $table->timestamps();
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
