<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feed_receipts', function (Blueprint $table) {
            $table->id();

            $table->string('receipt_number')->unique();

            $table->foreignId('feed_product_id')
                ->constrained('feed_products')
                ->restrictOnDelete();

            $table->date('received_at');
            $table->string('supplier_name');
            $table->string('batch_number')->nullable();

            $table->unsignedInteger('quantity');
            $table->decimal('unit_cost', 12, 2)->nullable();

            $table->date('expires_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feed_receipts');
    }
};