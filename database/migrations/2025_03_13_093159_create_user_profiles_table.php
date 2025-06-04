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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('nik')->unique();
            $table->unsignedBigInteger('nuptk')->unique()->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('position')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->date('employment_start')->nullable();
            $table->date('employment_end')->nullable();
            $table->string('avatar')->nullable();
            $table->boolean('active')->nullable();
            $table->datetimes();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
