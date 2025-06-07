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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->integer('menu_group')->nullable();
            $table->string('name');
            $table->string('url')->nullable();
            $table->smallInteger('platform');
            $table->smallInteger('order')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('active')->nullable();
            $table->datetimes();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

            $table->index(['menu_group', 'platform', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
