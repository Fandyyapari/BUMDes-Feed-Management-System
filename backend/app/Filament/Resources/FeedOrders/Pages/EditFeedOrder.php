<?php

namespace App\Filament\Resources\FeedOrders\Pages;

use App\Filament\Resources\FeedOrders\FeedOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFeedOrder extends EditRecord
{
    protected static string $resource = FeedOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
