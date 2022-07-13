<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuoteInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained();
            $table->string('name');
            $table->string('company');
            $table->string('email');
            $table->string('landline');
            $table->string('cell_phone');
            $table->string('oportunity');
            $table->string('rank');
            $table->string('department')->nullable();
            $table->text('information')->nullable();
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
        Schema::dropIfExists('quote_information');
    }
}
