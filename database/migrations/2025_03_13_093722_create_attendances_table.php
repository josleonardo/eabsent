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
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate();
            $table->date('date');
            $table->time('sched_in')->nullable();
            $table->time('sched_out')->nullable();
            $table->time('actual_in')->nullable();
            $table->time('actual_out')->nullable();
            $table->smallInteger('status')->default(0)->index();
            $table->boolean('active')->default(true);
            $table->datetimes();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->cascadeOnUpdate();

            $table->unique(['user_id', 'date']); // ensure only one attendance per day per user
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
