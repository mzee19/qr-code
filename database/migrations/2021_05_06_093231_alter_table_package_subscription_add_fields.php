<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePackageSubscriptionAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `package_subscriptions`   
        ADD COLUMN `repetition` INT(11) NULL AFTER `end_date`,
        ADD COLUMN `payment_option` TINYINT(1) NULL   COMMENT '1 = Free, 2 = Paid' AFTER `repetition`;");
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
