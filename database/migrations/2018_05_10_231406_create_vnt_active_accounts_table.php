<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVntActiveAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vnt_active_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_admin_id')->unsigned();
            $table->foreign('user_admin_id')->references('user_id')->on('vnt_admin_user')->onDelete('cascade');
            $table->integer('user_moderator_id')->unsigned();
            $table->foreign('user_moderator_id')->references('user_id')->on('vnt_moderator_users')->onDelete('cascade');
            $table->integer('user_tour_guide_id')->unsigned();
            $table->foreign('user_tour_guide_id')->references('user_id')->on('vnt_tour_guide')->onDelete('cascade');
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
        Schema::dropIfExists('vnt_active_accounts');
    }
}
