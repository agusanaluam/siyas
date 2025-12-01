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
        Schema::create('t_mutation_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mutation_id')->index('t_deposit_detail_t_deposit_fk');
            $table->unsignedInteger('donation_id');
            $table->string('liq_number', 100);
            $table->unsignedInteger('amount');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_mutation_detail');
    }
};
