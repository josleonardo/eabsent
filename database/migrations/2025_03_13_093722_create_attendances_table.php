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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->date('date');
            $table->time('sched_in')->nullable();
            $table->time('sched_out')->nullable();
            $table->time('actual_in')->nullable();
            $table->time('actual_out')->nullable();
            $table->smallInteger('status')->default(0);
            $table->boolean('active')->nullable();
            $table->datetimes();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

            $table->unique(['user_id', 'date']); // ensure only one attendance per day per user
            $table->index(['user_id', 'date', 'status']); // index for faster queries on user_id, date, and status
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
