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
     * This migration handles the case where the database schema diverged from
     * the migration files. It adds missing columns and fills usernames.
     */
    public function up(): void
    {
        // Add columns that should exist based on the main migration but may be missing
        // because the migration file was rewritten after initial run.
        if (!Schema::hasColumn('users', 'full_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('full_name', 100)->after('id')->nullable();
            });

            // Copy existing 'name' data to 'full_name'
            if (Schema::hasColumn('users', 'name')) {
                DB::statement('UPDATE users SET full_name = name WHERE full_name IS NULL');
            }
        }

        if (!Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username')->nullable()->unique()->after('full_name');
            });
        }

        if (!Schema::hasColumn('users', 'phone_number')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone_number', 20)->nullable()->after('email_verified_at');
            });
        }

        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['super_admin', 'admin', 'user'])->default('user')->after('password');
            });
        }

        if (!Schema::hasColumn('users', 'account_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('account_status', ['active', 'suspended', 'pending'])->default('active')->after('role');
            });
        }

        if (!Schema::hasColumn('users', 'profile_photo')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('profile_photo', 255)->nullable()->after('account_status');
            });
        }

        if (!Schema::hasColumn('users', 'bio')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('bio')->nullable()->after('profile_photo');
            });
        }

        if (!Schema::hasColumn('users', 'address')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('address')->nullable()->after('bio');
            });
        }

        if (!Schema::hasColumn('users', 'last_login')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('last_login')->nullable()->after('address');
            });
        }

        if (!Schema::hasColumn('users', 'cover_photo_path')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('cover_photo_path', 2048)->nullable()->after('current_team_id');
            });
        }

        if (!Schema::hasColumn('users', 'social_links')) {
            Schema::table('users', function (Blueprint $table) {
                $table->json('social_links')->nullable()->after('cover_photo_path');
            });
        }

        if (!Schema::hasColumn('users', 'is_verified')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_verified')->default(false)->after('social_links');
            });
        }

        if (!Schema::hasColumn('users', 'google_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('google_id')->nullable()->after('is_verified');
            });
        }

        if (!Schema::hasColumn('users', 'github_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('github_id')->nullable()->after('google_id');
            });
        }

        if (!Schema::hasColumn('users', 'deleted_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Rename primary key if needed (id -> id_user)
        // Note: This is complex and may not be needed if the app works with 'id'
        // Skipping PK rename to avoid breaking existing FK constraints

        // Fill usernames for existing users
        $users = DB::table('users')->whereNull('username')->get();
        foreach ($users as $user) {
            $name = $user->full_name ?? $user->name ?? 'user';
            $username = \Illuminate\Support\Str::slug($name);
            if (empty($username)) {
                $username = 'user';
            }
            $count = DB::table('users')
                ->where('username', 'LIKE', "{$username}%")
                ->whereNotNull('username')
                ->count();
            $finalUsername = $count ? "{$username}-{$count}" : $username;

            DB::table('users')->where('id', $user->id ?? $user->id_user ?? 0)->update([
                'username' => $finalUsername,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
