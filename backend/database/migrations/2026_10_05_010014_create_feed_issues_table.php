<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feed_issues', function (Blueprint $table) {
            $table->id();

            $table->string('issue_number')->unique();

            $table->foreignId('feed_product_id')
                ->constrained('feed_products')
                ->restrictOnDelete();

            $table->date('issued_at');
            $table->unsignedInteger('quantity');

            $table->string('reason', 50);
            $table->string('recipient_name')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feed_issues');
    }
};