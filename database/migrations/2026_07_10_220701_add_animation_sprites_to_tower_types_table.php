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
            $table->text('base_sprite')->nullable()->after('sprite');
            $table->text('head_sprite')->nullable()->after('base_sprite');
            $table->text('muzzle_flash_sprite')->nullable()->after('head_sprite');
            $table->text('projectile_sprite')->nullable()->after('muzzle_flash_sprite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tower_types', function (Blueprint $table) {
            $table->dropColumn(['base_sprite', 'head_sprite', 'muzzle_flash_sprite', 'projectile_sprite']);
        });
    }
};
