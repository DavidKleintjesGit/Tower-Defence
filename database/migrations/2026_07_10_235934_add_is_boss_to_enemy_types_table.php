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
        Schema::table('enemy_types', function (Blueprint $table) {
            $table->boolean('is_boss')->default(false)->after('bounty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enemy_types', function (Blueprint $table) {
            $table->dropColumn('is_boss');
        });
    }
};
