<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_campaign');
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            $table->unique(['id_campaign', 'id_user']);
            $table->foreign('id_campaign')->references('id_campaign')->on('campaigns')->cascadeOnDelete();
            $table->foreign('id_user')->references('id_user')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_likes');
    }
};
