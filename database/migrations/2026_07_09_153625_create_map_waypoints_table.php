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
        Schema::create('map_waypoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('map_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sequence');
            $table->unsignedInteger('x');
            $table->unsignedInteger('y');
            $table->enum('type', ['entrance', 'path', 'corner', 'exit']);
            $table->string('lane')->default('main');
            $table->timestamps();

            $table->unique(['map_id', 'lane', 'sequence']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('map_waypoints');
    }
};
