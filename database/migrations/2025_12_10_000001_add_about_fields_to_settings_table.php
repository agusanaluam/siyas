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
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'about_content')) {
                $table->longText('about_content')->nullable()->after('description');
            }
            if (!Schema::hasColumn('settings', 'about_photo')) {
                $table->string('about_photo')->nullable()->after('about_content');
            }
            if (!Schema::hasColumn('settings', 'about_service')) {
                $table->json('about_service')->nullable()->after('about_photo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'about_service')) {
                $table->dropColumn('about_service');
            }
            if (Schema::hasColumn('settings', 'about_photo')) {
                $table->dropColumn('about_photo');
            }
            if (Schema::hasColumn('settings', 'about_content')) {
                $table->dropColumn('about_content');
            }
        });
    }
};

