<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('withdrawals');

        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id('id_withdrawal');
            $table->unsignedBigInteger('id_campaign');
            $table->unsignedBigInteger('id_user');
            $table->decimal('amount', 15, 2);
            $table->string('bank_name', 100);
            $table->string('account_number', 50);
            $table->string('account_holder', 100);
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'paid'])->default('pending');
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('id_campaign');
            $table->index('id_user');
            $table->index('status');

            $table->foreign('id_campaign')
                ->references('id_campaign')->on('campaigns')
                ->onDelete('cascade');
            $table->foreign('id_user')
                ->references('id_user')->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');

        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};
