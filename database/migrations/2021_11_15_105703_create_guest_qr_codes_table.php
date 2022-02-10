<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestQrCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_qr_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->ipAddress('ip_address')->nullable();
            $table->string('type',20)->nullable();
            $table->string('image',60)->nullable();
            $table->string('logo_image',60)->nullable();
            $table->text('fields')->nullable();
            $table->text('data')->nullable();
            $table->text('config')->nullable();
            $table->string('browser',30)->nullable();
            $table->string('city',30)->nullable();
            $table->string('country',30)->nullable();
            $table->string('platform',30)->nullable();
            $table->string('device',30)->nullable();
            $table->string('location',100)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guest_qr_codes');
    }
}
