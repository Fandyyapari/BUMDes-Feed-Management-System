<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['feed_receipts', 'feed_issues'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->timestamp('cancelled_at')->nullable();

                $table->text('cancellation_reason')->nullable();

                $table->foreignId('cancelled_by')
                    ->nullable()
                    ->constrained('users')
                    ->restrictOnDelete();
            });
        }
    }

    public function down(): void
    {
        foreach (['feed_receipts', 'feed_issues'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropConstrainedForeignId('cancelled_by');

                $table->dropColumn([
                    'cancelled_at',
                    'cancellation_reason',
                ]);
            });
        }
    }
};