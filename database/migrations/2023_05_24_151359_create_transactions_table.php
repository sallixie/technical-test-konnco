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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("user_id");
            $table->uuid("cart_id");
            $table->string("status")->default("pending");
            $table->bigInteger("total_biaya");
            $table->string("va_number")->nullable();
            $table->string("bill_key")->nullable();
            $table->string("biller_code")->nullable();
            $table->datetime("expiry_time")->nullable();
            $table->string("bank");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
