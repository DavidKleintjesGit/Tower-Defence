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
            $table->unsignedInteger('cost')->default(50)->after('fire_interval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tower_types', function (Blueprint $table) {
            $table->dropColumn('cost');
        });
    }
};
