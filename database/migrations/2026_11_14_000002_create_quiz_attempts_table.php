<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('correct_answers');
            $table->unsignedInteger('total_questions');
            $table->unsignedInteger('score');
            $table->json('details')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('quiz_attempts'); }
};
