<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('lead');
            $table->boolean('iva_by_item')->default(false);
            $table->boolean('show_total')->default(true);
            $table->text('logo')->nullable();
            $table->integer('type_days')->default(0);
            $table->boolean('pending_odoo')->default(false);
            $table->foreignId('company_id')->constrained();
            // $table->boolean('client');
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
        Schema::dropIfExists('quotes');
    }
}
