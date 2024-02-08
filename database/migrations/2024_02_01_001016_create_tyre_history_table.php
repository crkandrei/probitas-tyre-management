<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tyre_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('tyre_id');
            $table->string('action');
            $table->timestamp('action_date');

            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('tyre_id')->references('id')->on('tyres');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tyre_history');
    }
};
