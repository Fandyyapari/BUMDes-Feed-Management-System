<?php

namespace App\Filament\Resources\FeedSales\Pages;

use App\Filament\Resources\FeedSales\FeedSaleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeedSales extends ListRecords
{
    protected static string $resource = FeedSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
