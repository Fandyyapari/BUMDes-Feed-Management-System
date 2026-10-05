<?php

namespace App\Filament\Resources\FeedReceipts\Pages;

use App\Filament\Resources\FeedReceipts\FeedReceiptResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeedReceipts extends ListRecords
{
    protected static string $resource = FeedReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
