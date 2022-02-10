<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePaymentsAddDiscountRelatedFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `payments`   
          ADD COLUMN `vat_country_code` VARCHAR(2) NULL AFTER `vat_amount`,
          ADD COLUMN `discount_percentage` TINYINT(1) NULL AFTER `vat_country_code`,
          ADD COLUMN `discount_amount` DOUBLE NULL AFTER `discount_percentage`,
          ADD COLUMN `reseller` VARCHAR(100) NULL AFTER `discount_amount`,
          ADD COLUMN `voucher` VARCHAR(50) NULL AFTER `reseller`;");
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
