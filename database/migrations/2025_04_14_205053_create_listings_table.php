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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->string('listing_number')->unique();
            $table->string('title');
            $table->text('description');
            $table->foreignId('neighborhood_id')->constrained('neighborhoods')->onDelete('cascade');
            $table->foreignId('deed_type_id')->constrained('deed_types')->onDelete('cascade');
            $table->foreignId('property_type_id')->constrained('property_types')->onDelete('cascade');
            $table->foreignId('listing_type_id')->constrained('listing_types')->onDelete('cascade');
            $table->string('block_number');
            $table->string('parcel_number');
            $table->decimal('price', 15, 2);
            $table->decimal('square_meters', 8, 2);
            $table->decimal('price_per_square_meter', 15, 2)->nullable();
            $table->boolean('is_loan_eligible')->default(false);
            $table->enum('publication_status', ['published', 'inactive', 'archived'])->default('published');
            $table->enum('listing_status', ['available', 'sold', 'rented'])->default('available');
            $table->text('full_address');
            $table->date('listing_date');
            $table->date('update_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
