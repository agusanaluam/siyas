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
        Schema::table('t_donation', function (Blueprint $table) {
            $table->integer('volunteer_id')->nullable()->change();
            $table->integer('user_id')->nullable()->after('volunteer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_donation', function (Blueprint $table) {
            $table->integer('volunteer_id')->nullable(false)->change();
            $table->dropColumn('user_id');
        });
    }
};
