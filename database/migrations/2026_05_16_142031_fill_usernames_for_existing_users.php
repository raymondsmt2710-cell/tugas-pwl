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
        $users = \App\Models\User::whereNull('username')->get();
        foreach ($users as $user) {
            $username = \Illuminate\Support\Str::slug($user->name);
            $count = \App\Models\User::where('username', 'LIKE', "{$username}%")->count();
            $user->username = $count ? "{$username}-{$count}" : $username;
            $user->save();
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
