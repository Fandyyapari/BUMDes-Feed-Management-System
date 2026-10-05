<?php

namespace App\Filament\Resources\FeedSales;

use App\Filament\Resources\FeedSales\Pages\CreateFeedSale;
use App\Filament\Resources\FeedSales\Pages\EditFeedSale;
use App\Filament\Resources\FeedSales\Pages\ListFeedSales;
use App\Filament\Resources\FeedSales\Pages\ViewFeedSale;
use App\Filament\Resources\FeedSales\Schemas\FeedSaleForm;
use App\Filament\Resources\FeedSales\Schemas\FeedSaleInfolist;
use App\Filament\Resources\FeedSales\Tables\FeedSalesTable;
use App\Models\FeedSale;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FeedSaleResource extends Resource
{
    protected static ?string $navigationLabel = 'Penjualan Pakan';

    protected static ?string $modelLabel = 'Penjualan Pakan';

    protected static ?string $pluralModelLabel = 'Penjualan Pakan';

    protected static ?string $model = FeedSale::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'sale_number';

    public static function form(Schema $schema): Schema
    {
        return FeedSaleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FeedSaleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeedSalesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFeedSales::route('/'),
            'create' => CreateFeedSale::route('/create'),
            'view' => ViewFeedSale::route('/{record}'),
            'edit' => EditFeedSale::route('/{record}/edit'),
        ];
    }

    public static function canEdit(
        \Illuminate\Database\Eloquent\Model $record
    ): bool {
        return false;
    }

    public static function canDelete(
        \Illuminate\Database\Eloquent\Model $record
    ): bool {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
