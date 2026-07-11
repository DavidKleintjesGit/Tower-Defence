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
        Schema::table('tile_types', function (Blueprint $table) {
            $table->decimal('render_scale', 4, 2)->default(1.0)->after('footprint_height');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tile_types', function (Blueprint $table) {
            $table->dropColumn('render_scale');
        });
    }
};
