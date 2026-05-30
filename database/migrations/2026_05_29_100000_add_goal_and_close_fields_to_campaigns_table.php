<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update status enum to include new statuses
        DB::statement("ALTER TABLE campaigns MODIFY COLUMN status ENUM('draft', 'pending', 'approved', 'goal_reached', 'closed', 'rejected', 'archived') DEFAULT 'draft'");

        Schema::table('campaigns', function (Blueprint $table) {
            $table->timestamp('goal_reached_at')->nullable()->after('end_date');
            $table->timestamp('closed_at')->nullable()->after('goal_reached_at');
            $table->unsignedBigInteger('closed_by')->nullable()->after('closed_at');
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['goal_reached_at', 'closed_at', 'closed_by']);
        });

        DB::statement("ALTER TABLE campaigns MODIFY COLUMN status ENUM('draft', 'pending', 'approved', 'rejected', 'completed') DEFAULT 'draft'");
    }
};
