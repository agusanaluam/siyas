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
        Schema::create('m_volunteer', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('group_id');
            $table->string('name', 150);
            $table->string('sex', 10)->nullable();
            $table->string('email', 100)->nullable();
            $table->unsignedBigInteger('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('notes')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('spv')->default(false);
            $table->string('address_code', 100)->nullable();
            $table->string('profile_picture')->nullable();
            $table->unsignedBigInteger('nik')->nullable();
            $table->date('birth_date')->nullable();
            $table->float('points')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_volunteer');
    }
};
