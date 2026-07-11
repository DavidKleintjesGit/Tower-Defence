<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('map_objects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('map_id')->constrained()->cascadeOnDelete();
            $table->string('tile_code');
            $table->unsignedInteger('x');
            $table->unsignedInteger('y');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_objects');
    }
};
