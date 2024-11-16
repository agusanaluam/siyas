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
        Schema::create('t_donation', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('volunteer_id');
            $table->string('liq_number', 15);
            $table->string('donatur_name', 150);
            $table->text('donatur_address')->nullable();
            $table->string('donatur_phone', 15)->nullable();
            $table->text('description')->nullable();
            $table->integer('total_amount')->default(0);
            $table->dateTime('trans_date')->useCurrent();
            $table->boolean('via_transfer')->default(false);
            $table->string('reference_code')->nullable();
            $table->string('reference_picture')->nullable();
            $table->string('status', 20);
            $table->timestamp('approve_lead')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_donation');
    }
};
