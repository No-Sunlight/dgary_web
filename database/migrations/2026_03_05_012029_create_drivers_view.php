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
    DB::statement("
CREATE OR REPLACE VIEW `drivers` AS 
SELECT 
    u.id, 
    u.name, 
    u.email, 
    u.password 
FROM users u 
JOIN model_has_roles mhr ON mhr.model_id = u.id
JOIN roles r ON r.id = mhr.role_id 
WHERE r.name = 'Repartidor'");
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `drivers`");
    }
};
