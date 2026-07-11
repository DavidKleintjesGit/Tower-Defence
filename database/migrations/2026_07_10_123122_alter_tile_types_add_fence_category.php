<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE tile_types MODIFY category ENUM('ground', 'road', 'fence', 'decoration') NOT NULL");

        DB::table('tile_types')->where('code', 'fence')->update(['category' => 'fence']);
    }

    public function down(): void
    {
        DB::table('tile_types')->where('category', 'fence')->update(['category' => 'decoration']);

        DB::statement("ALTER TABLE tile_types MODIFY category ENUM('ground', 'road', 'decoration') NOT NULL");
    }
};
