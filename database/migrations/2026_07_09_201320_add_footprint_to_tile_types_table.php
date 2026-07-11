<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tile_types', function (Blueprint $table) {
            $table->unsignedTinyInteger('footprint_width')->default(1)->after('sprite');
            $table->unsignedTinyInteger('footprint_height')->default(1)->after('footprint_width');
        });
    }

    public function down(): void
    {
        Schema::table('tile_types', function (Blueprint $table) {
            $table->dropColumn(['footprint_width', 'footprint_height']);
        });
    }
};
