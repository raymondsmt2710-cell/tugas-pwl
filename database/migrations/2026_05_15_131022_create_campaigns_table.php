<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id('id_campaign');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_category');
            
            // Judul & Deskripsi
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->string('short_description', 500);
            $table->longText('description');
            
            // Nilai Donasi
            $table->decimal('target_amount', 15, 2);
            $table->decimal('minimum_donation', 15, 2)->default(0);
            $table->decimal('collected_amount', 15, 2)->default(0);
            $table->decimal('withdrawal_amount', 15, 2)->default(0);
            $table->decimal('available_balance', 15, 2)->default(0);
            
            // Status
            $table->enum('campaign_status', ['draft', 'active', 'finished', 'closed', 'suspended'])->default('draft');
            $table->enum('verification_status', ['draft', 'pending', 'active', 'rejected', 'expired'])->default('draft');
            
            // Media
            $table->string('video_url', 500)->nullable();
            
            // Tanggal
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date');
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes('deleted_at');
            
            // Indexes
            $table->index('id_user');
            $table->index('id_category');
            $table->index('campaign_status');
            $table->index('verification_status');
            
            // Foreign Keys
            $table->foreign('id_user')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('id_category')
                ->references('id_category')->on('categories')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};