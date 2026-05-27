<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update payment_status enum to match new statuses
        DB::statement("ALTER TABLE donations MODIFY COLUMN payment_status ENUM('pending', 'paid', 'failed', 'expired', 'cancelled') DEFAULT 'pending'");

        Schema::table('donations', function (Blueprint $table) {
            if (!Schema::hasColumn('donations', 'donation_amount')) {
                $table->decimal('donation_amount', 15, 2)->default(0)->after('donor_message');
            }
            if (!Schema::hasColumn('donations', 'order_id')) {
                $table->string('order_id', 100)->nullable()->unique()->after('payment_token');
            }
            if (!Schema::hasColumn('donations', 'is_anonymous')) {
                $table->boolean('is_anonymous')->default(false)->after('donor_message');
            }
            if (!Schema::hasColumn('donations', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_token');
            }
        });
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE donations MODIFY COLUMN payment_status ENUM('pending', 'success', 'failed', 'cancelled') DEFAULT 'pending'");

        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['donation_amount', 'order_id', 'is_anonymous', 'paid_at']);
        });
    }
};
