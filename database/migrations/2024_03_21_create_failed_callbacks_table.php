<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('failed_callbacks', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->json('payload');
            $table->timestamp('created_at');
            $table->boolean('is_processed')->default(false);
            $table->timestamp('processed_at')->nullable();
            $table->text('error_message')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('failed_callbacks');
    }
}; 