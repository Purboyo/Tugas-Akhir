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
    Schema::table('reminders', function (Blueprint $table) {
        $table->enum('status', ['pending', 'missed', 'completed'])->default('pending');
    });
}

    /**
     * Reverse the migrations.
     */
public function down()
{
    Schema::table('reminders', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}
};
