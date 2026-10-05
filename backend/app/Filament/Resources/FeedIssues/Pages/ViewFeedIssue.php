<?php

namespace App\Filament\Resources\FeedIssues\Pages;

use App\Filament\Resources\FeedIssues\FeedIssueResource;
use Filament\Resources\Pages\ViewRecord;

class ViewFeedIssue extends ViewRecord
{
    protected static string $resource = FeedIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}