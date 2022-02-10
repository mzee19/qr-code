<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUsersAddLastActiveAtAndDisabledAtFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users`   
          ADD COLUMN `last_active_at` TIMESTAMP NULL AFTER `last_quota_revised`,
          ADD COLUMN `disabled_at` TIMESTAMP NULL AFTER `last_active_at`;
        ");
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
