<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size')->default(0);
            $table->timestamps();

            $table->index('campaign_id');
            $table->foreign('campaign_id')
                ->references('id_campaign')->on('campaigns')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_documents');
    }
};
