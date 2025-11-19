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
        Schema::create('product_spec_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_spec_group_id')->constrained()->onDelete('cascade');
            $table->string('slug')->unique();
            $table->string('name'); // Changed from 'label'
            $table->string('input_type')->default('text');
            $table->json('options')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
        });
    }
    /**
     */
    public function down(): void
    {
        Schema::dropIfExists('product_spec_items');
    }
};
