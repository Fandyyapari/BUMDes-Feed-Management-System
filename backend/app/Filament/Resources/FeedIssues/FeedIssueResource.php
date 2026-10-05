<?php

namespace App\Filament\Resources\FeedIssues;

use App\Filament\Resources\FeedIssues\Pages\CreateFeedIssue;
use App\Filament\Resources\FeedIssues\Pages\ListFeedIssues;
use App\Filament\Resources\FeedIssues\Pages\ViewFeedIssue;
use App\Filament\Resources\FeedIssues\Schemas\FeedIssueForm;
use App\Filament\Resources\FeedIssues\Schemas\FeedIssueInfolist;
use App\Filament\Resources\FeedIssues\Tables\FeedIssuesTable;
use App\Models\FeedIssue;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class FeedIssueResource extends Resource
{
    protected static ?string $model = FeedIssue::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Pakan Keluar';

    protected static ?string $modelLabel = 'Pakan Keluar';

    protected static ?string $pluralModelLabel = 'Pakan Keluar';

    protected static ?string $recordTitleAttribute = 'issue_number';

    public static function form(Schema $schema): Schema
    {
        return FeedIssueForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FeedIssueInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeedIssuesTable::configure($table);
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFeedIssues::route('/'),
            'create' => CreateFeedIssue::route('/create'),
            'view' => ViewFeedIssue::route('/{record}'),
        ];
    }
}