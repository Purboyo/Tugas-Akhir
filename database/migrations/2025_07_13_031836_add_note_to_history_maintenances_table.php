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
    Schema::table('history_maintenances', function ($table) {
        $table->text('note')->nullable()->after('status');
    });
}
    /**
     * Reverse the migrations.
     */
public function down()
{
    Schema::table('history_maintenances', function ($table) {
        $table->dropColumn('note');
    });
}
};
