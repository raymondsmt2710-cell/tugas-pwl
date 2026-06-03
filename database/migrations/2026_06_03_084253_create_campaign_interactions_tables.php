<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Campaign Likes
        Schema::create('campaign_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_campaign');
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            $table->unique(['id_campaign', 'id_user']);
            $table->foreign('id_campaign')->references('id_campaign')->on('campaigns')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });

        // Campaign Comments
        Schema::create('campaign_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_campaign');
            $table->unsignedBigInteger('id_user');
            $table->text('comment');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_campaign')->references('id_campaign')->on('campaigns')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });

        // Campaign Reports
        Schema::create('campaign_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_campaign');
            $table->unsignedBigInteger('id_user');
            $table->string('reason');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('id_campaign')->references('id_campaign')->on('campaigns')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_reports');
        Schema::dropIfExists('campaign_comments');
        Schema::dropIfExists('campaign_likes');
    }
};
