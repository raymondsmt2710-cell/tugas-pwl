<?php

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
        if (!Schema::hasTable('campaign_comments')) {
            return;
        }

        if (!Schema::hasColumn('campaign_comments', 'deleted_at')) {
            Schema::table('campaign_comments', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('campaign_comments') && Schema::hasColumn('campaign_comments', 'deleted_at')) {
            Schema::table('campaign_comments', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
