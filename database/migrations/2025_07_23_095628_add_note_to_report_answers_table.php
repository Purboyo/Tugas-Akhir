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
Schema::table('report_answers', function (Blueprint $table) {
    $table->text('note')->nullable()->after('answer_text'); // untuk keterangan jika skor rendah
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_answers', function (Blueprint $table) {
            //
        });
    }
};
