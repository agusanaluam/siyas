<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ramadan_habit_completions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('habit_id');
            $table->unsignedBigInteger('user_id');
            $table->date('completed_date');
            $table->timestamps();

            $table->foreign('habit_id')->references('id')->on('ramadan_habits')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['habit_id', 'completed_date']);
            $table->index(['user_id', 'completed_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ramadan_habit_completions');
    }
};
