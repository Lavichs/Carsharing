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
        Schema::create('operations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('sum');             
            $table->unsignedInteger('type');            // Rent, Fine, Replenishment, Cashback

            $table->uuid('rent_id')->nullable();
            $table->uuid('user_id')->nullable(false);
            
            $table->index('rent_id', 'operation_rent_idx');
            $table->index('user_id', 'operation_user_idx');
            $table->foreign('rent_id', 'operation_rent_fk')->on('rents')->references('id')->onDelete('cascade');
            $table->foreign('user_id', 'operation_user_fk')->on('users')->references('id')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
