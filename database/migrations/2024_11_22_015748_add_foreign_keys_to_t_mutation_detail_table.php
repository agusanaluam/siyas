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
        Schema::table('t_mutation_detail', function (Blueprint $table) {
            $table->foreign(['mutation_id'], 't_deposit_detail_t_deposit_FK')->references(['id'])->on('t_mutation')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_mutation_detail', function (Blueprint $table) {
            $table->dropForeign('t_deposit_detail_t_deposit_FK');
        });
    }
};
