<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE tile_types MODIFY category ENUM('ground', 'road', 'decoration') NOT NULL");

        DB::table('tile_types')->where('code', 'road')->update(['category' => 'road']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('tile_types')->where('category', 'road')->update(['category' => 'ground']);

        DB::statement("ALTER TABLE tile_types MODIFY category ENUM('ground', 'decoration') NOT NULL");
    }
};
