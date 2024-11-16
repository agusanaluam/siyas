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
        Schema::create('m_program_category', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('pic', 150);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->bigInteger('target_total')->default(0);
            $table->integer('total_amount')->default(0);
            $table->boolean('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_program_category');
    }
};
