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
        Schema::create('m_program', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('category_id');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->boolean('status')->nullable()->comment('1 : Pending, 2 : Running :  3 Closing');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('pic', 150);
            $table->unsignedBigInteger('target_amount')->default(0);
            $table->unsignedInteger('target_object')->nullable()->default(0)->comment('Jumlah Penerima Manfaat');
            $table->unsignedBigInteger('total_amount')->nullable()->default(0);
            $table->string('campaign_picture')->nullable();
            $table->integer('close_type')->comment('1 : end_date, 2 : target_amount');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_program');
    }
};
