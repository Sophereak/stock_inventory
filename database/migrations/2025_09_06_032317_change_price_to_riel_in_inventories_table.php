<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inventories', function (Blueprint $table) {
            // Change price from decimal to integer for Riel
            $table->integer('price')->change();
        });
    }

    public function down()
    {
        Schema::table('inventories', function (Blueprint $table) {
            // Change back to decimal if rolling back
            $table->decimal('price', 8, 2)->change();
        });
    }
};