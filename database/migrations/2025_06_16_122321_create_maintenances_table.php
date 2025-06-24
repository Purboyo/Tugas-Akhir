<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('maintenances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('reminder_id')->constrained()->onDelete('cascade');
        // $table->foreignId('laboratory_id')->constrained()->onDelete('cascade');
        // $table->foreignId('user_id')->constrained()->onDelete('cascade'); // teknisi
        $table->text('note')->nullable(); // keterangan umum
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
