<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDrink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drink', function (Blueprint $table) {
            $table->bigIncrements("drink_id");
            $table->string("name");
            $table->text("description");
            $table->double("caffeine",20,4);
            $table->string("slug");
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
        Schema::dropIfExists('drink');
    }
}
