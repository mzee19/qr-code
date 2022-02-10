<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUsersAddPackageRelatedFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users`  
          ADD COLUMN `dynamic_qr_codes` VARCHAR(50) NULL AFTER `temp_zip_file`,
          ADD COLUMN `static_qr_codes` VARCHAR(50) NULL AFTER `dynamic_qr_codes`,
          ADD COLUMN `qr_code_scans` VARCHAR(50) NULL AFTER `static_qr_codes`,
          ADD COLUMN `bulk_import_limit` VARCHAR(50) NULL AFTER `qr_code_scans`,
          ADD COLUMN `expired_package_disclaimer` TINYINT(1) DEFAULT 0  NULL AFTER `bulk_import_limit`,
          ADD COLUMN `last_quota_revised` TIMESTAMP NULL COMMENT 'Revised quota date of user every month when user subscribe anuual package' AFTER `expired_package_disclaimer`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
