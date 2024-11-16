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
        Schema::create('m_member', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nik', 16)->unique('uniq_nik');
            $table->string('nkk', 16)->nullable();
            $table->string('name');
            $table->char('sex', 2);
            $table->string('birth_place', 225);
            $table->date('birth_date');
            $table->date('reg_date');
            $table->text('address');
            $table->string('address_code', 15);
            $table->string('zip_code', 6)->nullable();
            $table->char('marital_status', 2);
            $table->integer('edu_level_id');
            $table->integer('edu_major_id')->nullable();
            $table->integer('edu_institute_id')->nullable();
            $table->string('job');
            $table->string('job_institute')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_job')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_job')->nullable();
            $table->string('phone_number', 14)->nullable();
            $table->string('email', 150)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->softDeletes()->nullable(false);

            $table->index(['nkk', 'nik'], 'nkk_nik_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_member');
    }
};
