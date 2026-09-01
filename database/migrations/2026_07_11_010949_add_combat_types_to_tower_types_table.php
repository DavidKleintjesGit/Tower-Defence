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
        Schema::table('tower_types', function (Blueprint $table) {
            $table->boolean('splash_damage')->default(false)->after('fire_interval');
            $table->boolean('multi_target')->default(false)->after('splash_damage');
            $table->boolean('targets_ground')->default(true)->after('multi_target');
            $table->boolean('targets_air')->default(false)->after('targets_ground');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tower_types', function (Blueprint $table) {
            $table->dropColumn(['splash_damage', 'multi_target', 'targets_ground', 'targets_air']);
        });
    }
};
