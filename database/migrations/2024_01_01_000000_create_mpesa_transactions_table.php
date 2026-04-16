<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mpesa_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->index();
            $table->string('phone');
            $table->decimal('amount', 10, 2);
            $table->string('merchant_request_id')->nullable();
            $table->string('checkout_request_id')->nullable()->index();
            $table->string('mpesa_receipt')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->integer('result_code')->nullable();
            $table->string('result_desc')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mpesa_transactions');
    }
};
