<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('how_it_works', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['galang_dana', 'donasi']);
            $table->integer('step_number');
            $table->string('title');
            $table->text('description');
            $table->string('icon');
            $table->string('color')->nullable();
            $table->string('icon_color')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('how_it_works');
    }
};
