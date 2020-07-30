<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableConsumption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumption', function (Blueprint $table) {
            $table->bigIncrements("consumption_id");
            $table->unsignedBigInteger("drink_id");
            $table->unsignedBigInteger("consumer_id");
            $table->timestamps();

            $table->foreign("drink_id")->references("drink_id")->on("drink");
            $table->foreign("consumer_id")->references("consumer_id")->on("consumer");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consumption');
    }
}
