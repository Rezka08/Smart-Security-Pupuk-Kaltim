<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_check_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('check_id')->constrained('inventory_checks')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->enum('condition', ['Baik', 'Rusak']);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_check_details');
    }
};