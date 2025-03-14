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
            $table->integer('user_id')->index();
            $table->integer('schedule_id')->nullable();
            $table->tinyInteger('day_of_week'); // 0 = Sunday, ..., 6 = Saturday
            $table->date('date')->index();
            $table->time('sched_check_in')->nullable();
            $table->time('sched_check_out')->nullable();
            $table->time('real_check_in')->nullable();
            $table->time('real_check_out')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('attendances');
    }
};
