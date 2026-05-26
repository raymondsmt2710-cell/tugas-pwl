<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id('id_donation');
            $table->unsignedBigInteger('id_campaign');
            $table->unsignedBigInteger('id_user')->nullable();
            
            // Data Donatur
            $table->string('donor_name', 100);
            $table->string('donor_email', 100);
            $table->text('donor_message')->nullable();
            
            // Pembayaran
            $table->enum('payment_status', ['pending', 'success', 'failed', 'cancelled'])->default('pending');
            $table->string('payment_method', 50)->nullable(); // credit_card, bank_transfer, e_wallet
            $table->string('payment_token', 255)->nullable();
            
            // Admin & Reward
            $table->boolean('admin_flag')->default(false);
            $table->string('reward_pledges', 255)->nullable();
            $table->dateTime('next_payment')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes('deleted_at');
            
            // Indexes
            $table->index('id_campaign');
            $table->index('id_user');
            $table->index('payment_status');
            $table->index('created_at');
            
            // Foreign Keys
            $table->foreign('id_campaign')
                ->references('id_campaign')->on('campaigns')
                ->onDelete('cascade');
            $table->foreign('id_user')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};