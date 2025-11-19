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
        Schema::create('product_spec_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['name', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_spec_groups');
    }
};
