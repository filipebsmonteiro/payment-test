<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->double('value');
            $table->enum('status', ['Processando', 'Cancelado', 'Processado']);
            $table->string('observation')->nullable();
            $table->unsignedBigInteger('origin')->nullable();
            $table->unsignedBigInteger('destination');

            $table->foreign('origin')->references('id')->on('accounts');
            $table->foreign('destination')->references('id')->on('accounts');

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
        Schema::dropIfExists('transactions');
    }
}
