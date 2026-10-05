<?php

namespace App\Filament\Resources\FeedReceipts\Pages;

use App\Filament\Resources\FeedReceipts\FeedReceiptResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFeedReceipt extends EditRecord
{
    protected static string $resource = FeedReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
