<?php

namespace App\Filament\Resources\FeedIssues\Pages;

use App\Filament\Resources\FeedIssues\FeedIssueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeedIssues extends ListRecords
{
    protected static string $resource = FeedIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Catat Pakan Keluar'),
        ];
    }
}