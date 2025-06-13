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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("user_first_name");
            $table->string("user_last_name");
            $table->string("phone");
            $table->string("email");
            $table->string("city");
            $table->string("governorate");
            $table->text("address");
            $table->string("country");
            $table->string("postal_code");
            $table->string("gift_recipient_name")->nullable();
            $table->string("gift_recipient_phone")->nullable();
            $table->string("gift_recipient_city")->nullable();
            $table->string("gift_recipient_governorate")->nullable();
            $table->text("gift_recipient_address")->nullable();
            $table->string("gift_recipient_country")->nullable();
            $table->string("gift_recipient_postal_code")->nullable();
            $table->enum("status",["pending","canceled",'paid',"delivered","out for delivery","shipped","packing"])->default("pending");
            $table->foreignId("user_id")->constrained("users","id")->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
