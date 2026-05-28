<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawal_otps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('withdrawal_id');
            $table->string('otp_code', 6);
            $table->timestamp('expires_at');
            $table->boolean('is_used')->default(false);
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'withdrawal_id']);
            $table->foreign('user_id')->references('id_user')->on('users')->cascadeOnDelete();
            $table->foreign('withdrawal_id')->references('id_withdrawal')->on('withdrawals')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_otps');
    }
};
