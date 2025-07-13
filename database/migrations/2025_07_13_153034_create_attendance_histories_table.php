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
            $table->integer('user_id');
            $table->date('date');
            $table->time('actual_in')->nullable();
            $table->time('actual_out')->nullable();
            $table->smallInteger('status');
            $table->string('source');
            $table->integer('source_id')->nullable();
            $table->text('change_reason')->nullable();
            $table->dateTime('changed_at')->nullable()->index();
            $table->integer('changed_by')->nullable()->index();

            $table->index(['user_id', 'date', 'status']);
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
