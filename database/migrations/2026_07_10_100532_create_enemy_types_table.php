<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enemy_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('hp');
            $table->float('speed_multiplier')->default(1);
            $table->string('color');
            $table->text('sprite');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enemy_types');
    }
};
