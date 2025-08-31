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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('reason');
            $table->tinyInteger('status')->nullable()->index();
            $table->dateTime('approved_at')->nullable()->index();
            $table->integer('approved_by')->nullable()->index();
            $table->boolean('active')->nullable();
            $table->datetimes();
            $table->integer('created_by')->nullable()->index();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
