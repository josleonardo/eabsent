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
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('requires_description')->default(true);
            $table->boolean('active')->default(true);
            $table->datetimes();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('users')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
