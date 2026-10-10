<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feed_orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_number', 50)->unique();

            // Mencegah satu pengiriman form tercatat dua kali.
            $table->uuid('request_token')->unique();

            // Akun pelanggan yang membuat pesanan.
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('feed_product_id')
                ->constrained('feed_products')
                ->restrictOnDelete();

            // Salinan informasi saat pesanan dibuat.
            $table->string('customer_name');
            $table->string('customer_phone', 20);
            $table->string('customer_dusun', 100)->nullable();
            $table->text('customer_address')->nullable();

            $table->string('product_name');
            $table->decimal('weight_kg', 10, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_price', 15, 2);

            $table->string('status', 30)
                ->default('menunggu');

            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();

            // Pengurus yang mengonfirmasi atau menolak pesanan.
            $table->foreignId('processed_by')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            // Satu transaksi penjualan untuk satu pesanan.
            $table->foreignId('feed_sale_id')
                ->nullable()
                ->unique()
                ->constrained('feed_sales')
                ->restrictOnDelete();

            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feed_orders');
    }
};