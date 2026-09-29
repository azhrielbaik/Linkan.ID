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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('digital_products')->onDelete('cascade');
            $table->string('order_id')->unique(); // Satu order = satu review (unique constraint)
            $table->string('buyer_name');
            $table->string('buyer_email');
            $table->unsignedTinyInteger('rating'); // 1–5 bintang
            $table->text('comment')->nullable();   // Ulasan teks, opsional
            $table->boolean('is_visible')->default(true); // Seller/Admin visibility toggle
            $table->timestamps();

            // Index untuk query performa di halaman detail produk seller
            $table->index(['product_id', 'is_visible']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
