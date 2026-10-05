<?php

namespace App\Filament\Resources\FeedIssues\Pages;

use App\Filament\Resources\FeedIssues\FeedIssueResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFeedIssue extends EditRecord
{
    protected static string $resource = FeedIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
