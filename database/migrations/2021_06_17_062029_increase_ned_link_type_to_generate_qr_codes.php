<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IncreaseNedLinkTypeToGenerateQrCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generate_qr_codes', function (Blueprint $table) {
            DB::statement("ALTER TABLE `generate_qr_codes` CHANGE `ned_link` `ned_link` VARCHAR(100) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('generate_qr_codes', function (Blueprint $table) {
            //
        });
    }
}
