<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tower_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('damage');
            $table->float('range_tiles');
            $table->float('fire_interval');
            $table->string('color');
            $table->text('sprite');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tower_types');
    }
};
