<?php

namespace App\Filament\Resources\FeedProducts\Pages;

use App\Filament\Resources\FeedProducts\FeedProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeedProducts extends ListRecords
{
    protected static string $resource = FeedProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
