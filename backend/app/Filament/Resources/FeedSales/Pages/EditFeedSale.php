<?php

namespace App\Filament\Resources\FeedSales\Pages;

use App\Filament\Resources\FeedSales\FeedSaleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFeedSale extends EditRecord
{
    protected static string $resource = FeedSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
