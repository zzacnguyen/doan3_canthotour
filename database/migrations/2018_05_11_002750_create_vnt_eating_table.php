<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVntEatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vnt_eating', function (Blueprint $table) {
            $table->increments('id');
             $table->string('eat_name', 100);
            $table->string('eat_status', 10);
            $table->integer('service_id')->unsigned();
            $table->foreign('service_id')->references('id')->on('vnt_services')->onDelete('cascade');
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
        Schema::dropIfExists('vnt_eating');
    }
}
