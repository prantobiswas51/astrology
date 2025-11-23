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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['simple', 'grouped', 'booking', 'digital']);
            $table->string('name');
            $table->string('slug')->unique();
            $table->longText('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->longText('custom_html')->nullable();
            $table->json('fields')->nullable();   // dynamic custom fields
            
            $table->string('image1_path')->nullable();
            $table->string('image2_path')->nullable();
            $table->string('image3_path')->nullable();
            $table->string('image4_path')->nullable();

            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2)->nullable();

            $table->string('status')->default('draft'); // draft, private, published
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
