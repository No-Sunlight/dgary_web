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
        DB::statement("CREATE VIEW `drivers` AS select `u`.`id` AS `id`,`u`.`name` AS `name`,`u`.`email` AS `email`,`u`.`password` AS `password` from ((`heladeria`.`users` `u` join `heladeria`.`model_has_roles` `mhr` on(`mhr`.`model_id` = `u`.`id` and `mhr`.`model_type` = 'App\\Models\\User')) join `heladeria`.`roles` `r` on(`r`.`id` = `mhr`.`role_id`)) where `r`.`name` = 'Repartidor'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `drivers`");
    }
};
