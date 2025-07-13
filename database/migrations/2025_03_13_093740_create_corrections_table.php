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
            $table->date('date');
            $table->time('actual_in')->nullable();
            $table->time('actual_out')->nullable();
            $table->string('reason');
            $table->tinyInteger('status')->nullable();
            $table->dateTime('approved_at')->nullable()->index();
            $table->integer('approved_by')->nullable()->index();
            $table->boolean('active')->nullable();
            $table->datetimes();
            $table->integer('created_by')->nullable()->index();
            $table->integer('updated_by')->nullable();

            $table->index(['date', 'status']);
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
