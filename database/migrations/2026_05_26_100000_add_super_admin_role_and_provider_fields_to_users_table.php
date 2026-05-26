<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration ensures the super_admin role and OAuth provider fields exist.
     * It's safe to run whether the main migration already includes these or not.
     */
    public function up(): void
    {
        // Update role enum to include super_admin if not already present
        // Check current enum values
        $currentEnum = DB::select("SHOW COLUMNS FROM users WHERE Field = 'role'");
        if (!empty($currentEnum)) {
            $type = $currentEnum[0]->Type;
            if (strpos($type, 'super_admin') === false) {
                DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'user') DEFAULT 'user'");
            }
        }

        // Add provider fields if they don't exist
        if (!Schema::hasColumn('users', 'provider')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('provider')->nullable()->after('github_id');
            });
        }

        if (!Schema::hasColumn('users', 'provider_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('provider_id')->nullable()->after('provider');
            });
        }

        if (!Schema::hasColumn('users', 'avatar_url')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('avatar_url', 2048)->nullable()->after('provider_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert super_admin users to admin before removing enum value
        DB::table('users')->where('role', 'super_admin')->update(['role' => 'admin']);
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user') DEFAULT 'user'");

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['provider', 'provider_id', 'avatar_url']);
        });
    }
};
