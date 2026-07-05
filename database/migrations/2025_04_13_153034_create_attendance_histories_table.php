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
        Schema::create('attendance_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate();
            $table->date('date');
            $table->time('actual_in')->nullable();
            $table->time('actual_out')->nullable();
            $table->smallInteger('status')->default(0)->index();
            $table->string('source');
            $table->integer('source_id')->nullable();
            $table->text('change_reason')->nullable();
            $table->dateTime('changed_at')->nullable();
            $table->foreignId('changed_by')->constrained('users')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_histories');
    }
};
