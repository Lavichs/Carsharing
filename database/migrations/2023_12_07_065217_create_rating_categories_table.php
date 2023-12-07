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
        Schema::create('rating_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('rating');  // rating required for rent * 100 (example: 420 -> 4.2+)
            $table->integer('rate');    // per-minute rate (example: 5rub/minute, 8rub/minute...)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_categories');
    }
};
