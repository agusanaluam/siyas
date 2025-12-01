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
        Schema::create('t_mutation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_number', 100);
            $table->dateTime('trans_date');
            $table->unsignedInteger('total_amount');
            $table->unsignedInteger('total_liq')->nullable();
            $table->text('description')->nullable();
            $table->string('status', 100);
            $table->timestamp('approve_date')->nullable();
            $table->unsignedInteger('approve_by')->nullable();
            $table->unsignedInteger('created_by');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_mutation');
    }
};
