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
            $table->string('type')->default('script'); // image, script, code
            $table->string('position'); // e.g., home_header, sidebar, footer
            $table->string('image')->nullable();
            $table->string('storage_type')->default('backblaze')->nullable();
            $table->string('link')->nullable();
            $table->text('script')->nullable(); // For AdSense or other scripts
            $table->boolean('is_active')->default(true);
            $table->bigInteger('views')->default(0);
            $table->date('start_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['position', 'is_active']);
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
