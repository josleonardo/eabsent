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
        Schema::create('corrections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained()->cascadeOnUpdate();
            $table->time('correct_in')->nullable();
            $table->time('correct_out')->nullable();
            $table->smallInteger('correct_status')->nullable();
            $table->text('description');
            $table->smallInteger('status')->default(0)->index();
            $table->dateTime('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->cascadeOnUpdate();
            $table->datetimes();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->cascadeOnUpdate();

            $table->index(['attendance_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corrections');
    }
};
