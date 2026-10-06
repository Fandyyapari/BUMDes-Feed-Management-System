<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feed_products', function (Blueprint $table) {
            $table->text('composition')->nullable();
            $table->text('usage_instructions')->nullable();
            $table->text('storage_instructions')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('feed_products', function (Blueprint $table) {
            $table->dropColumn([
                'composition',
                'usage_instructions',
                'storage_instructions',
            ]);
        });
    }
};