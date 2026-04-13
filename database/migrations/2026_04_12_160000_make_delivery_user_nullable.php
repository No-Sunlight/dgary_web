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
        DB::statement('ALTER TABLE deliveries DROP FOREIGN KEY deliveries_user_id_foreign');
        DB::statement('ALTER TABLE deliveries MODIFY user_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE deliveries ADD CONSTRAINT deliveries_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE RESTRICT ON DELETE SET NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $fallbackUserId = DB::table('users')->min('id');

        if ($fallbackUserId === null) {
            return;
        }

        DB::table('deliveries')->whereNull('user_id')->update(['user_id' => $fallbackUserId]);

        DB::statement('ALTER TABLE deliveries DROP FOREIGN KEY deliveries_user_id_foreign');
        DB::statement('ALTER TABLE deliveries MODIFY user_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE deliveries ADD CONSTRAINT deliveries_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE RESTRICT ON DELETE RESTRICT');
    }
};
