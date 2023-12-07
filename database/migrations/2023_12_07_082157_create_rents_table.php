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
        Schema::create('rents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('duration');        // rental duration (minutes)
            $table->dateTime('date_of_rent');
            $table->unsignedInteger('status_id');          // Open, Closed, ClosedWithIncident
            
            $table->uuid('car_id')->nullable(false);
            $table->uuid('user_id')->nullable(false);
            
            $table->index('car_id', 'rent_car_idx');
            $table->index('user_id', 'rent_user_idx');

            $table->foreign('car_id', 'rent_car_fk')->on('cars')->references('id');
            $table->foreign('user_id', 'rent_user_fk')->on('users')->references('id');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rents');
    }
};
