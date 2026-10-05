<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feed_sales', function (Blueprint $table) {
            $table->id();

            $table->string('sale_number')->unique();

            $table->foreignId('feed_product_id')
                ->constrained('feed_products')
                ->restrictOnDelete();

            $table->date('sold_at');
            $table->string('customer_name');
            $table->string('customer_phone', 30)->nullable();

            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_price', 15, 2);

            $table->string('payment_method', 30);
            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->foreignId('cancelled_by')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feed_sales');
    }
};