<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCropDataToGenerateQrCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generate_qr_codes', function (Blueprint $table) {
            $table->tinyInteger('crop')->default(false)->after('ned_link');
            $table->text('crop_data')->nullable()->after('crop');
            $table->string('transparent_background')->nullable()->after('crop_data');
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
