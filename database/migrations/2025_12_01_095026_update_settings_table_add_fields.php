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
        // Cek apakah tabel sudah ada
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                // Tambah id jika belum ada
                if (!Schema::hasColumn('settings', 'id')) {
                    $table->id()->first();
                }
                // Tambah field gmaps
                if (!Schema::hasColumn('settings', 'gmaps')) {
                    $table->text('gmaps')->nullable()->after('address');
                }
                // Tambah timestamps jika belum ada
                if (!Schema::hasColumn('settings', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'gmaps')) {
                $table->dropColumn('gmaps');
            }
            if (Schema::hasColumn('settings', 'created_at')) {
                $table->dropTimestamps();
            }
        });
    }
};
