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
        Schema::create('cars', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('status');        // The status of the car: moving, waiting, under repair, etc.
            $table->unsignedInteger('engineType');         // Engine type: Internal combustion engine, electric, hybrid
            $table->string('number', 6);              // Three-digit car number
            $table->string('region', 3);              // The region in the car number
            $table->unsignedInteger('accidents')->default(0);   // Number of incidents
            $table->date('date_of_create');           // Date of production of the car
            $table->unique('number');

            $table->unsignedInteger('manufacturer_id');
            $table->unsignedInteger('model_id');
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('category_id');

            $table->index('manufacturer_id', 'car_manufacturer_idx');
            $table->index('model_id', 'car_model_idx');
            $table->index('brand_id', 'car_brand_idx');
            $table->index('category_id', 'car_category_idx');

            $table->foreign('manufacturer_id', 'car_manufacturer_idx')->on('manufacturers')->references('id');
            $table->foreign('model_id', 'car_model_fk')->on('model_cars')->references('id');
            $table->foreign('brand_id', 'car_brand_fk')->on('brands')->references('id');
            $table->foreign('category_id', 'car_category_idx')->on('rating_categories')->references('id');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
