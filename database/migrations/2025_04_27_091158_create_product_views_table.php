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
        Schema::create('product_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('visit_count')->default(0);
            $table->timestamp('last_visit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_views');
    }
};
