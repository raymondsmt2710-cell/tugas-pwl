<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaign_reports', function (Blueprint $table) {
            $table->enum('status', ['pending', 'reviewed', 'resolved', 'dismissed'])
                  ->default('pending')
                  ->after('description');
            $table->text('admin_notes')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('campaign_reports', function (Blueprint $table) {
            $table->dropColumn(['status', 'admin_notes']);
        });
    }
};
