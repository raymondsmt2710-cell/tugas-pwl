<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            // Privacy
            $table->boolean('show_profile_publicly')->default(true);
            $table->boolean('show_followers_count')->default(true);
            $table->boolean('show_following_count')->default(true);
            // Email Notifications
            $table->boolean('notify_donation_received')->default(true);
            $table->boolean('notify_campaign_approved')->default(true);
            $table->boolean('notify_withdrawal_approved')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id_user')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
