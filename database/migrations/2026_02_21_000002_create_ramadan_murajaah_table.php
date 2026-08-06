<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ramadan_murajaah', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('surah_number');
            $table->string('surah_name');
            $table->integer('ayah_number');
            $table->text('ayah_ar');
            $table->text('ayah_tr');
            $table->text('ayah_idn');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'surah_number', 'ayah_number']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ramadan_murajaah');
    }
};
