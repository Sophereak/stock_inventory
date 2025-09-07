<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Add this line

return new class extends Migration
{
    public function up()
    {
        // For SQLite compatibility, we'll create a new temporary table
        Schema::create('inventories_temp', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('quantity');
            $table->integer('price'); // Changed to integer for Riel
            $table->enum('clothing_type', [
                'shirt', 'pants', 'skirt', 'dress', 'sweater', 
                'jacket', 'uniform', 'pe_kit', 'tie', 'blazer'
            ])->default('shirt');
            $table->enum('size', [
                'XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 
                '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16'
            ])->nullable();
            $table->enum('gender', ['boys', 'girls', 'unisex'])->default('unisex');
            $table->string('color')->nullable();
            $table->string('school_house')->nullable();
            $table->timestamps();
        });

        // Copy data from old table to new table
        if (Schema::hasTable('inventories')) {
            $inventories = DB::table('inventories')->get();
            foreach ($inventories as $item) {
                DB::table('inventories_temp')->insert([
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'price' => (int) round($item->price), // Convert to integer
                    'clothing_type' => $item->clothing_type,
                    'size' => $item->size,
                    'gender' => $item->gender,
                    'color' => $item->color,
                    'school_house' => $item->school_house,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ]);
            }
        }

        // Drop old table and rename new table
        Schema::dropIfExists('inventories');
        Schema::rename('inventories_temp', 'inventories');
    }

    public function down()
    {
        // Reverse the process if needed
        Schema::create('inventories_temp', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 8, 2); // Back to decimal
            $table->enum('clothing_type', [
                'shirt', 'pants', 'skirt', 'dress', 'sweater', 
                'jacket', 'uniform', 'pe_kit', 'tie', 'blazer'
            ])->default('shirt');
            $table->enum('size', [
                'XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 
                '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16'
            ])->nullable();
            $table->enum('gender', ['boys', 'girls', 'unisex'])->default('unisex');
            $table->string('color')->nullable();
            $table->string('school_house')->nullable();
            $table->timestamps();
        });

        // Copy data back
        if (Schema::hasTable('inventories')) {
            $inventories = DB::table('inventories')->get();
            foreach ($inventories as $item) {
                DB::table('inventories_temp')->insert([
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'price' => (float) $item->price, // Convert back to decimal
                    'clothing_type' => $item->clothing_type,
                    'size' => $item->size,
                    'gender' => $item->gender,
                    'color' => $item->color,
                    'school_house' => $item->school_house,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ]);
            }
        }

        Schema::dropIfExists('inventories');
        Schema::rename('inventories_temp', 'inventories');
    }
};