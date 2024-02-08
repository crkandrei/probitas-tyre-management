<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('storages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deposit_id')->constrained();
            $table->integer('row');
            $table->integer('column')->nullable();
            $table->integer('shelf')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('storage');
    }
};
