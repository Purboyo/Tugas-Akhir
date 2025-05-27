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
    Schema::create('reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('reporter_id')->constrained('reporters')->onDelete('cascade');
        $table->foreignId('pc_id')->constrained('pcs')->onDelete('cascade');
        $table->foreignId('form_id')->constrained('forms')->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
