<?php

namespace App\Filament\Resources\FeedProducts\Pages;

use App\Filament\Resources\FeedProducts\FeedProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFeedProduct extends EditRecord
{
    protected static string $resource = FeedProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
