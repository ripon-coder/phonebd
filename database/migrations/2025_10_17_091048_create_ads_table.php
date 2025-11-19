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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('position');
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->text('script')->nullable();
            $table->boolean('is_active')->default(true); // Changed from 'status' to 'is_active'
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Added softDeletes

            // Added index, assuming 'status' in the original instruction meant 'is_active'
            // and 'sort_order' was a placeholder or intended to be added.
            // For now, I'll use 'is_active' and omit 'sort_order' as it's not defined.
            $table->index(['title', 'position', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
