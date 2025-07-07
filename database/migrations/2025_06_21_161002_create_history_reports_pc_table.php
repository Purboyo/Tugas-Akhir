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
    Schema::create('history_reports_pc', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('pc_id');
        $table->unsignedBigInteger('technician_id')->nullable();
        $table->text('description')->nullable();
        $table->string('status', 10);
        $table->timestamps();

        $table->foreign('pc_id')->references('id')->on('pcs')->onDelete('cascade');
        $table->foreign('technician_id')->references('id')->on('users')->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_reports_pc');
    }
};
