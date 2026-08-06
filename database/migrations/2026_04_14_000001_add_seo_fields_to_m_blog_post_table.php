<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('m_blog_post', function (Blueprint $table) {
            $table->string('meta_description', 300)->nullable()->after('excerpt');
            $table->string('meta_keywords', 500)->nullable()->after('meta_description');
        });
    }

    public function down(): void
    {
        Schema::table('m_blog_post', function (Blueprint $table) {
            $table->dropColumn(['meta_description', 'meta_keywords']);
        });
    }
};
