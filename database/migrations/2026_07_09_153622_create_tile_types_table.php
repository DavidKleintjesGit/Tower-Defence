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
        Schema::create('tile_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('category', ['ground', 'decoration']);
            $table->string('label');
            $table->string('color');
            $table->boolean('is_buildable')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tile_types');
    }
};
