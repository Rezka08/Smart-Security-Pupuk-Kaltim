<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cctv_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('shift', ['A', 'B', 'C', 'D']);
            $table->dateTime('log_time');
            $table->string('camera_location');
            $table->text('incident_description');
            $table->text('action_taken');
            $table->enum('status', ['Aman', 'Tidak Aman']);
            $table->string('evidence_photo_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cctv_logs');
    }
};