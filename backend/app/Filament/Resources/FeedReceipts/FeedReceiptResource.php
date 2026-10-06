<?php

namespace App\Filament\Resources\FeedReceipts;

use App\Filament\Resources\FeedReceipts\Pages\CreateFeedReceipt;
use App\Filament\Resources\FeedReceipts\Pages\EditFeedReceipt;
use App\Filament\Resources\FeedReceipts\Pages\ListFeedReceipts;
use App\Filament\Resources\FeedReceipts\Pages\ViewFeedReceipt;
use App\Filament\Resources\FeedReceipts\Schemas\FeedReceiptForm;
use App\Filament\Resources\FeedReceipts\Schemas\FeedReceiptInfolist;
use App\Filament\Resources\FeedReceipts\Tables\FeedReceiptsTable;
use App\Models\FeedReceipt;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class FeedReceiptResource extends Resource
{
    protected static ?string $navigationLabel = 'Pakan Masuk';

    protected static ?string $modelLabel = 'Pakan Masuk';

    protected static ?string $pluralModelLabel = 'Pakan Masuk';

    protected static ?string $model = FeedReceipt::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'receipt_number';

    public static function form(Schema $schema): Schema
    {
        return FeedReceiptForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FeedReceiptInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeedReceiptsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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

    public static function getPages(): array
    {
        return [
            'index' => ListFeedReceipts::route('/'),
            'create' => CreateFeedReceipt::route('/create'),
            'view' => ViewFeedReceipt::route('/{record}'),
        ];
    }
}
