<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tyres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->string('car_number');
            $table->string('model');
            $table->string('size');
            $table->integer('quantity');
            $table->string('status');
            $table->boolean('hasRim');
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tyres');
    }
};
