<?php

namespace App\Filament\Resources\FeedProducts;

use App\Filament\Resources\FeedProducts\Pages\CreateFeedProduct;
use App\Filament\Resources\FeedProducts\Pages\EditFeedProduct;
use App\Filament\Resources\FeedProducts\Pages\ListFeedProducts;
use App\Filament\Resources\FeedProducts\Schemas\FeedProductForm;
use App\Filament\Resources\FeedProducts\Tables\FeedProductsTable;
use App\Models\FeedProduct;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FeedProductResource extends Resource
{
    protected static ?string $navigationLabel = 'Produk Pakan';

protected static ?string $modelLabel = 'Produk Pakan';

protected static ?string $pluralModelLabel = 'Produk Pakan';

    protected static ?string $model = FeedProduct::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return FeedProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeedProductsTable::configure($table);
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
            'index' => ListFeedProducts::route('/'),
            'create' => CreateFeedProduct::route('/create'),
            'edit' => EditFeedProduct::route('/{record}/edit'),
        ];
    }
}
