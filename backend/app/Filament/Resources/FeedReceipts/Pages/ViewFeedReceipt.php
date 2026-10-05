<?php

namespace App\Filament\Resources\FeedReceipts\Pages;

use App\Filament\Resources\FeedReceipts\FeedReceiptResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFeedReceipt extends ViewRecord
{
    protected static string $resource = FeedReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
