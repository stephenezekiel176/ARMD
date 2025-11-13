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
        // Legacy default migration left in repository. This migration has been
        // intentionally converted to a no-op to avoid creating tables that are
        // already defined by the project's custom migrations. Leave this file
        // in place only for history; the real users table is created by
        // 2024_11_05_000002_create_users_table.php.
        return;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op — nothing to drop here because this legacy migration was
        // disabled. The actual tables are managed by the project's
        // custom migrations.
        return;
    }
};
