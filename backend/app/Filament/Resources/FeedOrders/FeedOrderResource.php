<?php

namespace App\Filament\Resources\FeedOrders;

use App\Filament\Resources\FeedOrders\Pages\ListFeedOrders;
use App\Filament\Resources\FeedOrders\Pages\ViewFeedOrder;
use App\Filament\Resources\FeedOrders\Schemas\FeedOrderInfolist;
use App\Filament\Resources\FeedOrders\Tables\FeedOrdersTable;
use App\Models\FeedOrder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class FeedOrderResource extends Resource
{
    protected static ?string $model = FeedOrder::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedShoppingCart;

    protected static ?string $navigationLabel = 'Pesanan';

    protected static ?string $modelLabel = 'Pesanan';

    protected static ?string $pluralModelLabel = 'Pesanan';

    protected static ?string $recordTitleAttribute = 'order_number';

    public static function canViewAny(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && in_array(
                $user->fresh()?->role,
                ['admin', 'petugas'],
                true
            );
    }

    public static function canView(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canCreate(): bool
    {
        return false;
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

    public static function infolist(Schema $schema): Schema
    {
        return FeedOrderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeedOrdersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFeedOrders::route('/'),
            'view' => ViewFeedOrder::route('/{record}'),
        ];
    }
}