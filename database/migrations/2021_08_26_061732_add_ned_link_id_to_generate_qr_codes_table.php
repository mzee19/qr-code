<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNedLinkIdToGenerateQrCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generate_qr_codes', function (Blueprint $table) {
            $table->string('ned_link_back_half_id')->nullable()->after('ned_link');
            $table->string('ned_link_back_half')->nullable()->after('ned_link_back_half_id');
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
