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
        Schema::create('form_questions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('form_id')->constrained('forms')->onDelete('cascade');
        $table->text('question_text');
        $table->enum('type', ['text', 'number', 'checkbox', 'radio', 'textarea']);
        // $table->boolean('is_required')->default(false);
        $table->json('options')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_questions');
    }
};
