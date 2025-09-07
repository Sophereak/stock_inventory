<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inventories', function (Blueprint $table) {
            // Remove the generic category field
            $table->dropColumn('category');
            
            // Add clothing-specific fields
            $table->enum('clothing_type', [
                'shirt', 
                'pants', 
                'skirt', 
                'dress', 
                'sweater', 
                'jacket', 
                'uniform', 
                'pe_kit', 
                'tie', 
                'blazer'
            ])->default('shirt');
            
            $table->enum('size', [
                'XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 
                '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16'
            ])->nullable();
            
            $table->enum('gender', ['boys', 'girls', 'unisex'])->default('unisex');
            $table->string('color')->nullable();
            $table->string('school_house')->nullable();
        });
    }

    public function down()
    {
        Schema::table('inventories', function (Blueprint $table) {
            // Reverse the changes if needed
            $table->string('category');
            $table->dropColumn(['clothing_type', 'size', 'gender', 'color', 'school_house']);
        });
    }
};