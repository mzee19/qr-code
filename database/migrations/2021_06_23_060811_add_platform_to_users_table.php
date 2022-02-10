<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddPlatformToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement("ALTER TABLE `users` ADD COLUMN `platform` TINYINT(1) DEFAULT 1  NULL COMMENT '1- Web, 2- Mobile, 3- Thunderbird, 4- Outlook, 5- Move Immunity , 6- Ned Link, 7- aikQ, 8- Inbox, 9- Overmail, 10- Maili, 11- Product Immunity 12- Transfer Immunity' AFTER `temp_zip_file`;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
