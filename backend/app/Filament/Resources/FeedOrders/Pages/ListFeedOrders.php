<?php

namespace App\Filament\Resources\FeedOrders\Pages;

use App\Filament\Resources\FeedOrders\FeedOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListFeedOrders extends ListRecords
{
    protected static string $resource = FeedOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}