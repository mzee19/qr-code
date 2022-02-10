<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSubscribers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE TABLE `subscribers` (
          `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
          `email` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
          `created_at` TIMESTAMP NULL DEFAULT NULL,
          `updated_at` TIMESTAMP NULL DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_subscribers');
    }
}
