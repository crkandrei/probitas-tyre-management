<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tyres', function (Blueprint $table) {
            $table->unsignedBigInteger('storage_id')->nullable()->after('id');
            $table->foreign('storage_id')->references('id')->on('storages')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tyres', function (Blueprint $table) {
            //
        });
    }
};
