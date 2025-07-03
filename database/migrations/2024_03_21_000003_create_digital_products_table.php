<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('digital_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->enum('platform_type', ['upload', 'dropbox', 'gdrive', 'other']);
            $table->string('platform_url')->nullable();
            $table->string('platform_file')->nullable();
            $table->boolean('has_quantity_limit')->default(false);
            $table->integer('quantity')->nullable();
            $table->string('button_text');
            $table->boolean('is_active')->default(true);
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('digital_products');
    }
}; 