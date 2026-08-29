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
        Schema::table('digital_products', function (Blueprint $table) {
            $table->json('media_files')->nullable()->after('image');
            $table->enum('pricing_type', ['fixed', 'pwyw'])->default('fixed')->after('sale_price');
            $table->decimal('price_min', 10, 2)->nullable()->after('pricing_type');
            $table->decimal('price_max', 10, 2)->nullable()->after('price_min');
            $table->integer('quantity_min')->default(1)->after('has_quantity_limit');
            $table->boolean('is_scheduled')->default(false)->after('button_text');
            $table->dateTime('start_time')->nullable()->after('is_scheduled');
            $table->dateTime('end_time')->nullable()->after('start_time');
            $table->string('deliverable_type')->nullable()->after('end_time');
            $table->string('deliverable_url')->nullable()->after('deliverable_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('digital_products', function (Blueprint $table) {
            $table->dropColumn([
                'media_files', 'pricing_type', 'price_min', 'price_max', 
                'quantity_min', 'is_scheduled', 'start_time', 'end_time', 
                'deliverable_type', 'deliverable_url'
            ]);
        });
    }
};
