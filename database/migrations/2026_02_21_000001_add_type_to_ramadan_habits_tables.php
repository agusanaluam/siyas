<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ramadan_default_habits', function (Blueprint $table) {
            $table->string('type', 20)->default('positive')->after('sort_order');
        });

        Schema::table('ramadan_habits', function (Blueprint $table) {
            $table->string('type', 20)->default('positive')->after('points');
        });
    }

    public function down(): void
    {
        Schema::table('ramadan_default_habits', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('ramadan_habits', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
