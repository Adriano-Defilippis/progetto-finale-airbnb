<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apartments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->text('description')->nullable();
            $table->integer('rooms')->nullable();
            $table->integer('beds')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('mq')->nullable();
            $table->string('address')->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->integer('visualizations')->nullable()->deafult('0');
            $table->boolean('active')->nullable();
            $table->string('img_path')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apartments');

    }
}
